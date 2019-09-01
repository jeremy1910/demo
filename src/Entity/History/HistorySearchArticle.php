<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 29/08/19
 * Time: 19:52
 */

namespace App\Entity\History;



use App\Service\History\HistorySearchArticleService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistorySearchArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class HistorySearchArticle
{

    private $historySearchArticleService;

    public function __construct(HistorySearchArticleService $historySearchArticleService = null)
    {
        $this->historySearchArticleService = $historySearchArticleService;
    }

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
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $category;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="searched_articles")
     */
    private $by_user;



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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return HistorySearchArticle
     */
    public function setCategory($category)
    {
        $this->category = $category;
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

    /**
     * @return mixed
     */
    public function getByUser()
    {
        return $this->by_user;
    }

    /**
     * @param mixed $by_user
     * @return HistorySearchArticle
     */
    public function setByUser($by_user)
    {
        $this->by_user = $by_user;
        return $this;
    }



    /**
     * @ORM\PreFlush()
     */
    public function purgeTable(){
       $this->historySearchArticleService->purgeHistory();
    }





}