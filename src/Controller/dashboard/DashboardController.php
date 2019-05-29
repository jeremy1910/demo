<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 13/05/19
 * Time: 23:11
 */

namespace App\Controller\dashboard;


use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Service\ArticleFilterHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    private $articleRepository;

    /**
     * @Route("/getData",name="getData")
     */
    public function getData(Request $request, ArticleFilterHandler $articleFilterHandler){

        dd($this->getUser()->getRoles());
        if($request->query->get('users')){
            /**
             * @var $currentUser User
             */
            $currentUser = $this->getUser();
            if ($currentUser->getRoles() == 'ROLE_ADMIN'){
                $articles = $this->articleRepository->findAll();
                $this->getDoctrine();
            }
            else{
                dd('il faut etre admin');
            }
        }
        $jsonResponse = $articleFilterHandler->articlesMapping($articles);
        new JsonResponse($jsonResponse);
    }
}