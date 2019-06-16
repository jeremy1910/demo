<?php


namespace App\Entity\Category\Filter;



use App\Entity\Interfaces\EntityFilterInterface;

class CategoryFilter implements EntityFilterInterface
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $libele;


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
     * @return CategoryFilter
     */
    public function setPageSelected(int $pageSelected): CategoryFilter
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
     * @return CategoryFilter
     */
    public function setNbResult(int $nbResult): CategoryFilter
    {
        $this->nbResult = $nbResult;
        return $this;
    }


    /**
     * @param int $id
     * @return CategoryFilter
     */
    public function setId(int $id): self
    {
        $this->id = $id;
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
     * @return null|string
     */
    public function getLibele(): ?string
    {
        return $this->libele;
    }

    /**
     * @param string $libele
     * @return CategoryFilter
     */
    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

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