<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 17/05/19
 * Time: 22:49
 */

namespace App\Service\Test;


use Doctrine\ORM\EntityManagerInterface;

class ServiceTest
{
    private $em;
    public function __construct(string $myString)
    {
        $this->em = $myString;
    }

}