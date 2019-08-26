<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Filter;
use App\Entity\GeneralSearch\Generalsearch;
use App\Form\Article\Filter\ArticleFilterType;
use App\Form\ArticleFilterNameType;
use App\Form\GeneralSearch\GeneralSearchType;
use App\Repository\ArticleRepository;
use App\Repository\GeneralSearchRepository;
use App\Service\ArticleFilterHandler;
use App\Service\ImageArticleHandler;
use App\Service\ImageProcessingHandler;
use App\Service\session\flashMessage\flashMessage;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ArticleController extends AbstractController
{

    /**
     * @var ObjectManager
     */
    private $em;
    private $flashMessage;
    private $entityManager;
    private $validator;

    public function __construct(ObjectManager $em, EntityManagerInterface $entityManager, flashMessage $flashMessage, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashMessage;
        $this->validator = $validator;

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

            if(!$this->allowNoImage($newArticle, $form)){
                return $this->render('article/article.html.twig', [
                    'form' => $form->createView()
                ]);
            }

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
            return $this->redirectToRoute('show_article', ['id' => $newArticle->getId()]);
        }

        return $this->render('article/article.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="edit_article")
     */

    public function editArticle(Article $article, Request $request, ImageArticleHandler $imageArticleHandler, ImageProcessingHandler $resizer)
    {

        if($this->isGranted('ARTICLE_EDIT', $article)) {
            $form = $this->createForm(FormArticleType::class, $article);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if(!$this->allowNoImage($article, $form)){
                    return $this->render('article/article.html.twig', [
                        'form' => $form->createView()
                    ]);
                }

                $image = $article->getImage();
                if ($image->getImageFile() !== null) {

                    $imageArticleHandler->save($image, $this->getParameter('kernel.project_dir') . "/public/images");

                    $resizer->resize($this->getParameter('kernel.project_dir') . "/public/images/" . $image->getFileName());
                }

                $article->setLastEditUser($this->getUser());
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
    public function filter(Request $request){
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
     * @Route("/article/delete/{id}", name="delete_article")
     */
    public function deleteArticle(Article $article = null){

        if($article === null){
            try {
                throw new \Exception("L'article que vous demandez n'existe pas");
            }
            catch (\Exception $e){
                $this->addFlash(
                    'error',
                    $e->getMessage()
                );
            }
        }

        $result = $this->delete($article);
        if($result === true)
        {
            $this->addFlash(
                'notice',
                'OKKKK'
            );

        }
        else{
            $this->addFlash(
                'alert',
                $result
            );

        }
        return $this->redirectToRoute('list_article');
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

            if ($this->isGranted('ARTICLE_DELETE', $article)) {
                try {
                    $this->delete($article);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Article supprimé avec succès');
                    return new JsonResponse([false, $flashMessage]);

                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {
                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible ! Vous n\'avaez pas les autoristaions necessaires');
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

        $formArticle = $this->createForm(ArticleFilterType::class, null, array(
            'action' => $this->generateUrl("articleFilter")));


        return $this->render('article/listArticles.html.twig', [
            'formArticle' => $formArticle->createView(),
            ]);

    }


    /**
     * @Route("article/getCard", name="getCard")
     */
    public function getArticleCardTemplate(){
        return $this->render('article/articleCardTemplate.html.twig');
    }


    /**
     * @Route("article/generalSearch", name="generalSearch")
     */


    public function generalSearch(Request $request, ArticleRepository $articleRepository)
    {

       $search = $request->get('search');
       if(strlen($search) >= 3)
       {
           $result = $articleRepository->searchAll($search);
           $serializer = SerializerBuilder::create()->build();
           return new JsonResponse($serializer->serialize($result, 'json', SerializationContext::create()->enableMaxDepthChecks()));
       }

        return $this->render("test/testTableStructure.html.twig", [
            //'form' => $form->createView(),
        ]);
    }
    /*
    public function genralSearch(Request $request, ArticleRepository $articleRepository){

        $generalSearch = new Generalsearch();
        $form = $this->createForm(GeneralSearchType::class, $generalSearch);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $result = $articleRepository->searchAll($generalSearch->getSearchString());
            $serializer = SerializerBuilder::create()->build();
            return new JsonResponse($serializer->serialize($result, 'json', SerializationContext::create()->enableMaxDepthChecks()));
        }

        return $this->render("test/testTableStructure.html.twig", [
            'form' => $form->createView(),
        ]);

    }
*/

    private function delete($article)
    {
        try {
            if ($this->isGranted('ARTICLE_DELETE', $article)) {

                $this->em->remove($article);
                $this->em->flush();
                return true;

            } else {
                throw new \Exception("Impossible de supprimer l'article, merci de vérifier que vous avez les aurorisations suffisantes");
            }
        } catch (\Exception $e){
            return $e->getMessage();
        }
    }



    private function allowNoImage(Article $article, Form $form){

        if($article->getId() === null AND $article->getImage()->getImageFile() === null){

            $form->get('image')->get('imageFile')->addError(new FormError('Veuillez selectionner une image'));
            return false;
        }
        else{
            return true;
        }

    }
}
