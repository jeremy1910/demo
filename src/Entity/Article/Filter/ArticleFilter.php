<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 07/06/19
 * Time: 22:45
 */

namespace App\Entity\Article\Filter;


use App\Entity\Interfaces\EntityFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ArticleFilter implements EntityFilterInterface
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var \DateTime|null
     */
    private $created_at_before;


    /**
     * @var \DateTime|null
     */
    private $created_at_after;

    /**
     * @var \DateTime|null
     */
    private $last_edit;

    /**
     * @var string|null
     */
    private $body;

    private $num_category;

    /**
     * @var boolean|null
     */
    private $image;

    /**
     * @var string|null
     */
    private $description;


    private $tags;




    private $user;

    /**
     * @var int
     */
    private $nbResult;

    /**
     * @var int
     */
    private $pageSelected;

    /**
     * @return int|null
     */
    public function getPageSelected(): ?int
    {
        return $this->pageSelected;
    }

    /**
     * @param int $pageSelected
     * @return ArticleFilter
     */
    public function setPageSelected(int $pageSelected): ArticleFilter
    {
        $this->pageSelected = $pageSelected;
        return $this;
    }





    /**
     * @return int|null
     */
    public function getNbResult(): ?int
    {
        return $this->nbResult;
    }

    /**
     * @param int $nbResult
     * @return ArticleFilter
     */
    public function setNbResult(int $nbResult): ArticleFilter
    {
        $this->nbResult = $nbResult;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     * @return ArticleFilter
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }


    public function getUser()
    {
        return $this->user;
    }


    public function setUser(?string $user)
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

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAtBefore(): ?string
    {
        return $this->created_at_before;
    }

    /**
     * @param \DateTime $created_at_before
     * @return ArticleFilter
     */
    public function setCreatedAtBefore(?\DateTime $created_at_before): ArticleFilter
    {
        $this->created_at_before = $created_at_before === null ? $created_at_before : $created_at_before->format('Y-m-d');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAtAfter(): ?string
    {
        return $this->created_at_after;
    }

    /**
     * @param \DateTime $created_at_after
     * @return ArticleFilter
     */
    public function setCreatedAtAfter(?\DateTime $created_at_after): ArticleFilter
    {
        $this->created_at_after = $created_at_after === null ? null : $created_at_after->format('Y-m-d');
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

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getNumCategory()
    {
        return $this->num_category;
    }

    public function setNumCategory(?array $num_category): self
    {
        $this->num_category = $num_category;

        return $this;
    }

    public function getImage(): ?bool
    {
        return $this->image;
    }

    public function setImage(?bool $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function iterate() {
        $tab = array();
        foreach ($this as $key => $value) {
            if(is_array($value)){
                foreach ($value as $key1 => $value1){
                    $tab[$key.$key1] = $value1;
                }
            }
            else{
                $tab[$key] = $value;
            }
        }

        return $tab;
    }


}