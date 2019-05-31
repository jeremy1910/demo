<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 26/05/19
 * Time: 22:54
 */

namespace App\Controller\CategoryController;


use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private $entityManager;



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
                        'notice',
                        'Category créée !'
                    );
                    $flashMessage = $this->get('session')->getFlashBag()->all();

                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $this->addFlash(
                    'notice',
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
                            'notice',
                            'Suppression impossible ! La categorie n\'existe pas.'
                        );
                        $flashMessage = $this->get('session')->getFlashBag()->all();
        
                        return new JsonResponse([false, $flashMessage]);
            }
                    
            if ($this->isGranted('CATEGORY_DELETE', $category)) {
                try {
                    
                    
                    $this->deleteCategory($category);
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

    private function deleteCategory(Category $category){
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
                $this->addFlash(
                    'notice',
                    'Impossible de renomer la categorie ! La category n\'existe pas.'
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }
            if ($this->isGranted('CATEGORY_DELETE', $category)) {
                try {
                    $this->editCategory($category, $libele);
                    $this->addFlash(
                        'notice',
                        'Category renomée !'
                    );
                    $flashMessage = $this->get('session')->getFlashBag()->all();

                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $this->addFlash(
                    'notice',
                    "Impossible de renomer la categorie ! Vous n'avaez pas les autoristaions necessaires"
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }
        }

    }

    private function editCategory(Category $category, string $newLibele){
        $category->setLibele($newLibele);
        $this->entityManager->flush();
    }

}