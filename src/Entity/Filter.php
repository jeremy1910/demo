<?php

namespace App\Entity;


class Filter
{



    private $category;
    private $recherche;

    /**
     * @return mixed
     */
    public function getRecherche()
    {
        return $this->recherche;
    }

    /**
     * @param mixed $recherche|null
     * @return Filter
     */
    public function setRecherche($recherche)
    {
        $this->recherche = $recherche;
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
     * @return Filter
     */
    public function setCategory($category): Filter
    {
        $this->category = $category;
        return $this;
    }




}