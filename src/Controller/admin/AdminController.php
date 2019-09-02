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

use App\Repository\HistorySearchArticleRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\Tag\TagFilterType;

class AdminController extends AbstractController
{

    private $articleRepository;
    private $categoryRepository;
    private $tagRepository;
    private $userRepository;
    private $historySearchArticleRepository;
    private $parameterBag;
    const NB_RECORDS_BY_PAGE = 15;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, TagRepository $tagRepository, UserRepository $userRepository, HistorySearchArticleRepository $historySearchArticleRepository, ParameterBagInterface $parameterBag)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;
        $this->historySearchArticleRepository = $historySearchArticleRepository;
        $this->parameterBag = $parameterBag;
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

        $nbTag = $this->tagRepository->getNumberOfTag();
        $lastCreatedTag = $this->tagRepository->getLastCreatedTag()[0];
        $lastEditTag = $this->tagRepository->getLastModifiedTag()[0];

        $nbUser = $this->userRepository->getNumberOfUser();
        $lastCreatedUser = $this->userRepository->getLastCreatedUser()[0];
        $lastEditUser = $this->userRepository->getLastModifiedUser()[0];

        $topArticle = $this->articleRepository->getTop10MostViewed();

        $historySearchArticle = $this->historySearchArticleRepository->getRecords($this->parameterBag->get('nb_search_history_records_admin_dashbord_to_show'));

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
            'nbTag' => $nbTag,
            'lastCreatedTag' => $lastCreatedTag,
            'lastEditTag' => $lastEditTag,
            'nbUser' => $nbUser,
            'lastCreatedUser' => $lastCreatedUser,
            'lastEditUser' => $lastEditUser,
            'topArticle' => $topArticle,
            'historySearchArticles' => $historySearchArticle,
        ]);

    }

    /**
     * @Route("/admin/search-history", name="admin-search-article-hystory")
     */
    public function search_history(Request $request){
        if($request->query->has('page')){
            $page = $request->query->get('page');
        }
        else{
            $page = 1;
        }
        $nbRecords = $this->historySearchArticleRepository->getNumberOfRecords();

        $nbPageTotal = ceil($nbRecords/self::NB_RECORDS_BY_PAGE);

        $from = ($page-1)*self::NB_RECORDS_BY_PAGE;
        $to = $page*self::NB_RECORDS_BY_PAGE;

        $historySearchArticles  = $this->historySearchArticleRepository->getRecordsFromTo($from, $to);
        return $this->render('admin/searchHistory.html.twig', [
            'historySearchArticles' => $historySearchArticles,
            'nbPage' => $nbPageTotal,
            'pageActuel' => $page,
        ]);


    }

    /**
     * @Route("/admin/test", name="admin_test")
     */
    public function admin_test(){

        $user = $this->userRepository->find(12);

        dd($user->getSearchedArticles()->getValues());
        return $this->render('test/testTableStructure.html.twig');
    }

}