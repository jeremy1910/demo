<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $allIdCategory = $this->categoryRepository->getAllId();



        return $this->render('index/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'allIdCategory' => $allIdCategory,
        ]);
    }

    /**
     * @Route("/index/getCategoryCards", name="getCategoryCards")
     */
    public function getCategoryCards(Request $request){
        $categoriesGiven = $request->query->get('categories');
        $categoriesGivenArray = explode(',', $categoriesGiven);
        if($categoriesGiven === null){
            return new JsonResponse([false, 'Category introuvable']);
        }else{

            $categories = $this->categoryRepository->findBy(['id' => $categoriesGivenArray]);


            return $this->render('index/index-category-column.html.twig', [
                'categories' => $categories,
            ]);
        }

    }
}
