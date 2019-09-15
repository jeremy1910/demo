<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @var ArticleRepository
     */
    private $repository;
    private $categoryRepository;
    const NB_COL_BY_SLIDE = 3;

    public function __construct(ArticleRepository $repository, CategoryRepository $categoryRepository)
    {

        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $articles = $this->repository->find5lastArticles();

        $nbCategory = $this->categoryRepository->getNumberOfCategory();
        $categories = $this->categoryRepository->findByCondition(null, 3, 0);



        return $this->render('index/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
}
