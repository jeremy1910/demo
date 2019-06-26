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

}
