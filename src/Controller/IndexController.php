<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
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
    private $tagRepository;
    const NB_COL_BY_SLIDE = 3;

    public function __construct(ArticleRepository $repository, CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {

        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $articles = $this->repository->find5lastArticles();

        $nbArticles = $this->repository->findNumberOfArticles();
        $categories = $this->categoryRepository->findByCondition(null, 3, 0);
        $allIdCategory = $this->categoryRepository->getAllId();
        $topArticle = $this->repository->getTop10MostViewed();
        $mostUsedtags = $this->tagRepository->getMostUsedTag();

        return $this->render('index/index.html.twig', [
            'nbArticles' => $nbArticles[0]['1'],
            'articles' => $articles,
            'categories' => $categories,
            'allIdCategory' => $allIdCategory,
            'topArticle' => $topArticle,
            'mostUsedtags' => $mostUsedtags,
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
