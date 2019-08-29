<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 29/08/19
 * Time: 19:52
 */

namespace App\Entity\History;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SearchHistoryRepository")
 */

class HistorySearchArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_after;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_before;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $tag;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $search_date;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return HistorySearchArticle
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return HistorySearchArticle
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     * @return HistorySearchArticle
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAfter()
    {
        return $this->created_after;
    }

    /**
     * @param mixed $created_after
     * @return HistorySearchArticle
     */
    public function setCreatedAfter($created_after)
    {
        $this->created_after = $created_after;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedBefore()
    {
        return $this->created_before;
    }

    /**
     * @param mixed $created_before
     * @return HistorySearchArticle
     */
    public function setCreatedBefore($created_before)
    {
        $this->created_before = $created_before;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     * @return HistorySearchArticle
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSearchDate()
    {
        return $this->search_date;
    }

    /**
     * @param mixed $search_date
     * @return HistorySearchArticle
     */
    public function setSearchDate($search_date)
    {
        $this->search_date = $search_date;
        return $this;
    }








}