<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Filter;
use App\Form\ArticleFilterNameType;
use App\Service\ArticleFilterHandler;
use App\Service\ImageArticleHandler;
use App\Service\ImageProcessingHandler;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\FormArticleType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Imagick;



class ArticleController extends AbstractController
{


    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {

        $this->em = $em;
    }

    /**
     * @Route("/new", name="create_article")
     */

    public function createArticle(Request $request, ImageProcessingHandler $resizer, ImageArticleHandler $imageArticleHandler)
    {

        $newArticle = new Article();
        $form = $this->createForm(FormArticleType::class, $newArticle);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $image = $newArticle->getImage();

            $imageArticleHandler->save($image,  $this->getParameter('kernel.project_dir')."/public/images");


            $resizer->resize($this->getParameter('kernel.project_dir')."/public/images/".$image->getFileName());

            $newArticle->setUser($this->getUser());

            $this->em->persist($newArticle);
            $this->em->flush();
            $this->addFlash(
                'notice',
                'Article créé !'
            );
            return $this->redirectToRoute('list_article');
        }

        return $this->render('article/article.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_article")
     */

    public function editArticle(Article $article, Request $request, ImageArticleHandler $imageArticleHandler, ImageProcessingHandler $resizer)
    {

        if($this->isGranted('EDIT', $article)) {
            $form = $this->createForm(FormArticleType::class, $article);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $image = $article->getImage();
                if ($image->getImageFile() !== null) {

                    $imageArticleHandler->save($image, $this->getParameter('kernel.project_dir') . "/public/images");


                    $resizer->resize($this->getParameter('kernel.project_dir') . "/public/images/" . $image->getFileName());
                }

                $article->setLastEdit(new \DateTime());
                $this->em->flush();
            }

            dump($form);
            return $this->render('article/article.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->addFlash('notice', 'Impossible de modifier cette article');
        return $this->redirectToRoute('index');
    }


    /**
     * @Route("/show/{id}", name="show_article")
     */

    public function showArticle(Article $article)
    {


        return $this->render('article/show.html.twig', [
            'article' => $article,

        ]);

    }


    /**
     * @Route("/getResult", name="getResult")
     */

    public function getResult(request $request, ArticleFilterHandler $articleFilterHandler)
    {


        $articles = $articleFilterHandler->setFilter($request)->getResult();


        $tabArticles = $articleFilterHandler->articlesMapping($articles);
        $numberPages = $articleFilterHandler->getNumberPage();
        $pageActive = $articleFilterHandler->getPageActive();

        $jsonResponse = [
            'numberPages' => $numberPages,
            'pageActive' => $pageActive,
            'articles' => $tabArticles,
        ];
        return new JsonResponse($jsonResponse);

    }

    /**
     * @Route("/delete/{id}", name="delete_article")
     */
    public function deleteArticle(Article $article){
        if($this->isGranted('DELETE', $article)) {
            $this->denyAccessUnlessGranted('DELETE', $article);
            $this->em->remove($article);
            $this->em->flush();


            //return $this->redirectToRoute('getResult');
            return new JsonResponse('OK');
        }

    }

    /**
     * @Route("/list", name="list_article")
     */

    public function listArticle(Request $request, ArticleFilterHandler $articleFilterHandler)
    {

        $cat = new Filter();
        $form = $this->createForm(ArticleFilterNameType::class, $cat);



        $numberPages = $articleFilterHandler->setFilter(null)->getNumberPage();
        $articles = $articleFilterHandler->getResult();
        dump($articles);

        return $this->render('article/listArticles.html.twig', [
            'numberPages' => $numberPages,
            'pageActive' => 1,
            'articles' => $articles,
            'form' => $form->createView(),
            ]);

    }


}
