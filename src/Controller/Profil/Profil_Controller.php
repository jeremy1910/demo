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
use Symfony\Component\Routing\Annotation\Route;

class Profil_Controller extends AbstractController
{
    private $historySearchArticleRepository;
    private $articleRepository;

    public function __construct(HistorySearchArticleRepository $historySearchArticleRepository, ArticleRepository $articleRepository)
    {
        $this->historySearchArticleRepository = $historySearchArticleRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function show_profil(){

        if($this->isGranted('ROLE_USER') && $this->isGranted('ROLE_ADMIN')){

            return $this->redirectToRoute('admin_page');

        }else if($this->isGranted('ROLE_USER')){

            /**
             * @var $user User
             */
            $user = $this->getUser();
            $historySearchArticles = $this->historySearchArticleRepository->findBy(['by_user' => $user->getId()], ['search_date' => 'desc'], 30);
            $created_articles = $this->articleRepository->findBy(['user' => $user->getId()], ['created_at' => 'desc'], 10);

            return $this->render('Profil/profil.html.twig', [
                'historySearchArticles' => $historySearchArticles,
                'created_articles' => $created_articles,
            ]);

        }

    }

}