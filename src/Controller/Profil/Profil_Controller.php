<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 22/09/19
 * Time: 17:17
 */

namespace App\Controller\Profil;


use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\HistorySearchArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Profil_Controller extends AbstractController
{
    private $historySearchArticleRepository;
    private $articleRepository;
    const NB_RECORDS_BY_PAGE = 15;

    public function __construct(HistorySearchArticleRepository $historySearchArticleRepository, ArticleRepository $articleRepository)
    {
        $this->historySearchArticleRepository = $historySearchArticleRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function show_profil(){

        if($this->isGranted('ROLE_USER')){

            /**
             * @var $user User
             */
            $user = $this->getUser();
            $historySearchArticles = $this->historySearchArticleRepository->findBy(['by_user' => $user->getId()], ['search_date' => 'desc'], 30);
            $created_articles = $this->articleRepository->findBy(['user' => $user->getId()], ['created_at' => 'desc'], 10);

            return $this->render('Profil/profil.html.twig', [
                'historySearchArticles' => $historySearchArticles,
                'created_articles' => $created_articles,
                'user' => $user,
            ]);

        }

    }

    /**
     * @Route("/profil/full/posts", name="show_full_posts_list")
     */
    public function show_full_posts_list(Request $request){

        if($this->isGranted('ROLE_USER')){
            /**
             * @var $user User
             */


            if($request->query->has('page')){
                $page = $request->query->get('page');
            }
            else{
                $page = 1;
            }
            $user = $this->getUser();

            $nbRecords = $this->articleRepository->findByCondition(['user' => $user->getUsername()], null, null, true);


            $nbPageTotal = ceil($nbRecords/self::NB_RECORDS_BY_PAGE);

            $from = ($page-1)*self::NB_RECORDS_BY_PAGE;
            $to = $page*self::NB_RECORDS_BY_PAGE;

            $full_list_Posts  = $this->articleRepository->findBy(['user' => $user], ['created_at' => 'desc'], $to, $from);

            return $this->render('Profil/profil_full_list_posts.html.twig',[
                'posts' => $full_list_Posts,
                'nbPage' => $nbPageTotal,
                'pageActuel' => $page,
                ]);
        }

    }


    /**
     * @Route("/profil/full/history", name="show_full_history")
     */
    public function show_full_posts_history(Request $request){

        if($this->isGranted('ROLE_USER')){
            /**
             * @var $user User
             */

            if($request->query->has('page')){
                $page = $request->query->get('page');
            }
            else{
                $page = 1;
            }
            $user = $this->getUser();

            $nbRecords = $this->historySearchArticleRepository->getNumberOfRecords();

            $nbPageTotal = ceil($nbRecords/self::NB_RECORDS_BY_PAGE);

            $from = ($page-1)*self::NB_RECORDS_BY_PAGE;
            $to = $page*self::NB_RECORDS_BY_PAGE;

            $full_list_History  = $this->historySearchArticleRepository->findBy(['by_user' => $user], ['search_date' => 'desc'], $to, $from);

            return $this->render('Profil/profil_full_list_history.html.twig',[
                'historySearchArticles' => $full_list_History,
                'nbPage' => $nbPageTotal,
                'pageActuel' => $page,
            ]);
        }

    }
}