<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 26/05/19
 * Time: 22:54
 */

namespace App\Controller\CategoryController;


use App\Entity\Category;
use App\Form\Category\CategoryType;
use App\Form\Category\Filter\CategoryFilterType;
use App\Service\ImageHandler;
use App\Service\ImageProcessingHandler;
use App\Service\session\flashMessage\flashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager, flashMessage $flashMessage, ImageHandler $imageHandler, ImageProcessingHandler $resizer)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashMessage;
        $this->imageHandler = $imageHandler;
        $this->resizer = $resizer;
    }

    private $entityManager;
    private $flashMessage;
    private $imageHandler;
    private $resizer;

    /**
     * @Route("/category/filter", name="categoryFilter")
     */
    public function filter(Request  $request){
        $categoryFilter = new Category\Filter\CategoryFilter();
        $form = $this->createForm(CategoryFilterType::class, $categoryFilter);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if($form->get('clickedButton')->getData() == "category_filter[search]") {
                $tabParameterRequest = array_merge(['t' => 'category'], $categoryFilter->iterate());
                unset($tabParameterRequest['createCategory']);

                return $this->redirectToRoute("get_info", $tabParameterRequest);
            }elseif ($form->get('clickedButton')->getData() == "category_filter[createCategory][submit]"){
                /**
                 * @var $category Category
                 */

                $category = $form->get('createCategory')->getData();

                $this->hadelCategoryImage($category);

                try{
                    $this->createCategory($category);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Categorie créée');
                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    $flashMessage = $this->flashMessage->getFlashMessage('danger', "une erreure c'est produit durant la création de la category");
                    return new JsonResponse([false, $flashMessage]);
                }
            }

        }
        else{
            dd($form->getErrors());
            return new JsonResponse(false, 'formulaire invalide');
        }
    }

    private function hadelCategoryImage(Category $category){
        if(PHP_OS_FAMILY !== "Windows"){
            $category_img_path = $this->getParameter('category_img_path');
        }
        else{
            $category_img_path = $this->getParameter('category_img_path_win');
        }

        $name = $this->imageHandler->save($category->getImage(), $this->getParameter('kernel.project_dir').$category_img_path);
        $category->setImagePath($name);

        $this->resizer->resize($this->getParameter('kernel.project_dir').$category_img_path.$category->getImagePath(), $this->getParameter('cotegory_img_dim'));

    }

    /**
     * @Route("/category/chgImageA", name="categoryCngImageA")
     */
    public function changeImage(Request $request){


        if ($request->request->has('categoryChangeImageForm')) {

            $image = $request->files->get('categoryChangeImageForm')['image'];
            $id = $request->request->get('categoryChangeImageForm')['id'];
            $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

            if ($this->isGranted('CATEGORY_EDIT', $category)) {
                $category->setImage($image);
                $this->hadelCategoryImage($category);
                $this->entityManager->flush();
            }
        }

    }

    /**
     * @Route("/addCategoryFormA", name="addCategoryFormA")
     */
    public function addCategoryAjaxByForm(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd("ici");
        }
    }
    /**
     * @Route("/addCategoryA", name="addCategoryA")
     */
    public function addCategoryAjax(Request $request){

        if ($request->query->has('libele') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('libele'))) {
            $category = new Category();

            if ($this->isGranted('CATEGORY_CREATE', $category)) {
                try {
                    $category->setLibele($request->query->get('libele'));
                    $this->createCategory($category);
                    $this->addFlash(
                        'success',
                        'Category créée !'
                    );
                    $flashMessage = $this->get('session')->getFlashBag()->all();

                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $this->addFlash(
                    'danger',
                    'Création impossible !'
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }
        }
        else{
            return new JsonResponse('Aucun élément à ajouter.');
        }



    }
    private function createCategory(Category $category){
        $category->setCreatedUser($this->getUser());
        $this->entityManager->persist($category);
        $this->entityManager->flush();

    }

    /**
     *
     * @Route("/rmCategoryA", name="rmCategoryA")
     * @param Request $request
     * @return mixed
     */
    public function removeCategoryAjax(Request $request){
        if ($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) {
            $repo = $this->entityManager->getRepository(Category::class);
            $id = $request->query->get('id');
            $category = $repo->find($id);
            if($category === NULL)
            {
                        $this->addFlash(
                            'danger',
                            'Suppression impossible ! La categorie n\'existe pas.'
                        );
                        $flashMessage = $this->get('session')->getFlashBag()->all();
        
                        return new JsonResponse([false, $flashMessage]);
            }
                    
            if ($this->isGranted('CATEGORY_DELETE', $category)) {
                try {
                    
                    $this->deleteCategory($category);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Categorie suprimmée');
                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){

                    $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible');
                    return new JsonResponse([false, $flashMessage]);
                }
            } else {

                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible ! Vous n\'avaez pas les autoristaions necessaires');
                return new JsonResponse([false, $flashMessage]);
            }
        
        }else{
            $this->addFlash(
                'danger',
                "Aucun élément à supprimer."
            );
            $flashMessage = $this->get('session')->getFlashBag()->all();

            return new JsonResponse([false, $flashMessage]);
        }

    }

    private function deleteCategory(Category $category){
        if($category->getImagePath() !== null){
            unlink($this->getParameter('kernel.project_dir').$this->getParameter('category_img_path').$category->getImagePath());
        }
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }


    /**
     * @Route("/edtCategoryA", name="edtCategoryA")
     * @param Request $request
     * @return JsonResponse
     */
    public function editCategoryAjax(Request $request){
        if (($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) AND ($request->query->has('libele') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('libele')))) {
            $repo = $this->entityManager->getRepository(Category::class);
            $id = $request->query->get('id');
            $libele = $request->query->get('libele');
            $category = $repo->find($id);
            if($category === NULL)
            {
                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Impossible de renomer la catégorie');

                return new JsonResponse([false, $flashMessage]);
            }
            if ($this->isGranted('CATEGORY_DELETE', $category)) {
                try {
                    $this->editCategory($category, $libele);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Catégorie renomée');
                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $flashMessage = $this->flashMessage->getFlashMessage('danger', "Impossible de renomer la categorie ! Vous n'avaez pas les autoristaions necessaires");
                return new JsonResponse([false, $flashMessage]);
            }
        }
        else{
            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Requete Incorrect');
            return new JsonResponse([false, $flashMessage]);

        }

    }

    private function editCategory(Category $category, string $newLibele){
        $category->setModifiedUser($this->getUser());
        $category->setLibele($newLibele);
        $this->entityManager->flush();
    }

}