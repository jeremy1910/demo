<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 01/05/19
 * Time: 23:38
 */

namespace App\Controller\admin;



use App\Entity\User;
use App\Form\Article\Filter\ArticleFilterType;
use App\Form\Category\CategoryType;
use App\Form\Category\Filter\CategoryFilterType;
use App\Form\User\Add\UserAddType;
use App\Form\User\Filter\UserFilterType;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use App\Form\Tag\TagFilterType;

class AdminController extends AbstractController
{

    private $articleRepository;
    private $categoryRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/admin", name="admin_page")
     */
    public function admin_page()
    {

        $formArticle = $this->createForm(ArticleFilterType::class, null, array(
            'action' => $this->generateUrl("articleFilter")));

        $formCategory = $this->createForm(CategoryFilterType::class, null, array(
            'action' => $this->generateUrl("categoryFilter")));


        $formTag = $this->createForm(TagFilterType::class, null, array(
            'action' => $this->generateUrl("tagFilter")));

        $formUser = $this->createForm(UserFilterType::class, null, array(
            'action' => $this->generateUrl("userFilter")));

        $formUserAdd = $this->createForm(UserAddType::class, null, array(
            'action' => $this->generateUrl("addUserA")
        ));


        $lastCreatedArticle = $this->articleRepository->getLastCreatedArticle()[0];
        $lastEditArticle = $this->articleRepository->getLastEditArticle()[0];
        $nbArticles = $this->articleRepository->findNumberOfArticles()[0][1];

        $nbCategory = $this->categoryRepository->getNumberOfCategory();
        $lastCreatedCategory = $this->categoryRepository->getLastCreatedCategory()[0];
        $lastEditCategory = $this->categoryRepository->getLastModifiedCategory()[0];




        return $this->render('admin/admin.html.twig', [
            'formArticle' => $formArticle->createView(),
            'formCategory' => $formCategory->createView(),
            'formUser' => $formUser->createView(),
            'formTag' => $formTag->createView(),
            'formUserAdd' => $formUserAdd->createView(),
            'lastCreatedArticle' => $lastCreatedArticle,
            'lastEditArticle' => $lastEditArticle,
            'nbArticles' => $nbArticles,
            'nbCategory' => $nbCategory,
            'lastCreatedCategory' => $lastCreatedCategory,
            'lastEditCategory' => $lastEditCategory,

        ]);

    }

    /**
     * @Route("/admin/test", name="admin_test")
     */
    public function admin_test(){

        return $this->render('test/testTableStructure.html.twig');
    }

}