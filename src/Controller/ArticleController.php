<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Filter;
use App\Form\Article\Filter\ArticleFilterType;
use App\Form\ArticleFilterNameType;
use App\Service\ArticleFilterHandler;
use App\Service\ImageArticleHandler;
use App\Service\ImageProcessingHandler;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
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


    private $entityManager;

    public function __construct(ObjectManager $em, EntityManagerInterface $entityManager)
    {
        $this->em = $em;
        $this->entityManager = $entityManager;
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
     * @Route("/article/filter", name="articleFilter")
     */
    public function filter(Request  $request){
        $articleFilter = new Article\Filter\ArticleFilter();
        $form = $this->createForm(ArticleFilterType::class, $articleFilter);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $tabParameterRequest = array_merge(['t' => 'article'], $articleFilter->iterate());

            return $this->redirectToRoute("get_info", $tabParameterRequest);
        }
        else{

            return new JsonResponse(false, 'formulaire invalide');
        }
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
     *
     * @Route("/rmArticleA", name="rmArticleA")
     * @param Request $request
     * @return mixed
     */
    public function removeCategoryAjax(Request $request){
        if ($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) {
            $repo = $this->entityManager->getRepository(Article::class);
            $id = $request->query->get('id');
            $article = $repo->find($id);
            if($article === NULL)
            {
                $this->addFlash(
                    'notice',
                    'Suppression impossible ! L\'article n\'existe pas.'
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }

            if ($this->isGranted('DELETE', $article)) {
                try {


                    $this->deleteArticle($article);
                    $this->addFlash(
                        'notice',
                        'Categorie suprimmée !'
                    );
                    $flashMessage = $this->get('session')->getFlashBag()->all();

                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $this->addFlash(
                    'notice',
                    "Suppression impossible ! Vous n'avaez pas les autoristaions necessaires"
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }

        }else{
            $this->addFlash(
                'notice',
                "Aucun élément à supprimer."
            );
            $flashMessage = $this->get('session')->getFlashBag()->all();

            return new JsonResponse([false, $flashMessage]);
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
