<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tagName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="tags", cascade={"persist"})
     * @Serializer\Exclude()
     */
    private $article;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;



    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified_at;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $created_user;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $modified_user;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): self
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return Tag
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @param mixed $modified_at
     * @return Tag
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getCreatedUser()
    {
        return $this->created_user;
    }

    /**
     * @param mixed $created_user
     * @return Tag
     */
    public function setCreatedUser($created_user)
    {
        $this->created_user = $created_user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifiedUser()
    {
        return $this->modified_user;
    }

    /**
     * @param mixed $modified_user
     * @return Tag
     */
    public function setModifiedUser($modified_user)
    {
        $this->modified_user = $modified_user;
        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }


    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->contains($article)) {
            $this->article->removeElement($article);
        }


        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function uniqueTag(LifecycleEventArgs $eventArgs){
        $entityManager = $eventArgs->getEntityManager();
        $repository    = $entityManager->getRepository(Tag::class);
            $find = $repository->findBy(['tagName' => $this->tagName]);
            if ($find != null){
               throw new \Exception('Tag déja existant');
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
            $this->modified_at = new \DateTime();
        }
    }

}
