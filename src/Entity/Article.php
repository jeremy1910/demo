<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


use Doctrine\ORM\Event\LifecycleEventArgs;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     indexes={
 *     @ORM\Index(columns={"title"}, flags={"fulltext"}),
 *     @ORM\Index(columns={"description"}, flags={"fulltext"}),
 *     @ORM\Index(columns={"body"}, flags={"fulltext"}),
 *     }
 * )
 */
class Article
{

    public function __construct()
    {

        $this->tags = new ArrayCollection();
        //$this->security = $security;
    }

    //private $security;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_edit;


    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $num_category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ImageArticle", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $image;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="article", cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $last_edit_User;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_view;

    /**
     * @return mixed
     */
    public function getLastEditUser()
    {
        return $this->last_edit_User;
    }

    /**
     * @param mixed $last_edit_User
     * @return Article
     */
    public function setLastEditUser($last_edit_User)
    {
        $this->last_edit_User = $last_edit_User;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;

    }


    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLastEdit(): ?\DateTimeInterface
    {
        return $this->last_edit;
    }

    public function setLastEdit(?\DateTimeInterface $last_edit): self
    {
        $this->last_edit = $last_edit;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getNumCategory(): ?Category
    {
        return $this->num_category;
    }

    public function setNumCategory(?Category $num_category): self
    {
        $this->num_category = $num_category;

        return $this;
    }

    public function getImage(): ?ImageArticle
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     *
     */
    public function getTags()
    {
        return $this->tags;
    }

   
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addArticle($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbView()
    {
        return $this->nb_view;
    }

    /**
     * @param mixed $nb_view
     * @return Article
     */
    public function setNbView($nb_view)
    {
        $this->nb_view = $nb_view;
        return $this;
    }


    public function addView(){
        $this->nb_view++;
    }


    /**
     * @ORM\PrePersist()
     */
    public function uniqueTag(LifecycleEventArgs $eventArgs){
        $entityManager = $eventArgs->getEntityManager();
        $repository    = $entityManager->getRepository(Tag::class);

        for ($i=0;$i<$this->tags->count();$i++)
        {
            $find = $repository->findBy(['tagName' => $this->tags->get($i)->getTagName()]);
            if ($find != null){
                $this->tags->set($i, $find[0]);
            }
        }


    }

    /**
     * @ORM\PreFlush()
     */
    public function setDate(){

        if(is_null($this->id)){
            $this->created_at = new \DateTime();
        }
        else{
            $this->last_edit = new \DateTime();
        }
    }


}
