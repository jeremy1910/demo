<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 24/06/19
 * Time: 21:29
 */

namespace App\Entity\Tag\Filter;


use App\Entity\Interfaces\EntityFilterInterface;

class TagFilter implements EntityFilterInterface
{

    /**
     * @var null|int
     */
    private $id;


    /**
     * @var null|string
     */
    private $tagName;


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
    public function getNbResult(): ?int
    {
        return $this->nbResult;
    }

    /**
     * @param int $nbResult
     * @return TagFilter
     */
    public function setNbResult(int $nbResult): TagFilter
    {
        $this->nbResult = $nbResult;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPageSelected(): ?int
    {
        return $this->pageSelected;
    }

    /**
     * @param int $pageSelected
     * @return TagFilter
     */
    public function setPageSelected(int $pageSelected): TagFilter
    {
        $this->pageSelected = $pageSelected;
        return $this;
    }





    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return TagFilter
     */
    public function setId(?int $id): TagFilter
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    /**
     * @param null|string $tagName
     * @return TagFilter
     */
    public function setTagName(?string $tagName): TagFilter
    {
        $this->tagName = $tagName;
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