<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @var ArticleRepository
     */
    private $repository;

    public function __construct(ArticleRepository $repository)
    {

        $this->repository = $repository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $articles = $this->repository->find10lastArticles();

        return $this->render('index/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
