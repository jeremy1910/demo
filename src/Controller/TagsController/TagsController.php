<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 30/05/19
 * Time: 22:19
 */

namespace App\Controller\TagsController;


use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("addTagA", name="addTagA")
     */
    public function addTagA(Request $request){


    }

    /**
     * @Route("delTagA", name="addTagA")
     */
    public function delTagA(Request $request){


    }

    /**
     * @Route("edtTagA", name="addTagA")
     */
    public function edtTagA(Request $request){


    }
}