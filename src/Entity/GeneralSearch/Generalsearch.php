<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 19/08/19
 * Time: 23:33
 */

namespace App\Entity\GeneralSearch;


class Generalsearch
{
    private $searchString;

    /**
     * @return mixed
     */
    public function getSearchString()
    {
        return $this->searchString;
    }

    /**
     * @param mixed $searchString
     * @return Generalsearch
     */
    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
        return $this;
    }




}