<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
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
    private $libele;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="num_category")
     * @Serializer\Exclude()
     */
    private $articles;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagePath;

    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $modified_user;


    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
     */
    public function setModifiedUser($modified_user)
    {
        $this->modified_user = $modified_user;
        return $this;
    }


    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setNumCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getNumCategory() === $this) {
                $article->setNumCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }




    /**
     * @return mixed
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param mixed $imagePath
     * @return Category
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
        return $this;
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
