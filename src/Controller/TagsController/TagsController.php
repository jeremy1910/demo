<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 30/05/19
 * Time: 22:19
 */

namespace App\Controller\TagsController;


use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("addTagA", name="addTagA")
     */
    public function addTagA(Request $request){

        if ($request->query->has('name') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('name'))) {
            $tag = new Tag();

            if ($this->isGranted('CATEGORY_CREATE', $tag)) {
                try {
                    $tag->setTagName($request->query->get('name'));
                    $this->createTag($tag);
                    $this->addFlash(
                        'notice',
                        'Tag créé !'
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
            return new JsonResponse('Any argument given');
        }

    }

    private function createTag(Tag $tag){
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    /**
     * @Route("delTagA", name="addTagA")
     */
    public function delTagA(Request $request){
        if ($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) {
            $repo = $this->entityManager->getRepository(Tag::class);
            $id = $request->query->get('id');
            $tag = $repo->find($id);
            if($tag === NULL)
            {
                $this->addFlash(
                    'notice',
                    'Suppression impossible ! Le Tag n\'existe pas.'
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }

            if ($this->isGranted('TAG_DELETE', $tag)) {
                try {


                    $this->deleteCategory($tag);
                    $this->addFlash(
                        'notice',
                        'Tag suprimmé !'
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

        }

    }

    private function deleteCategory(Tag $tag)
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }

    /**
     * @Route("edtTagA", name="addTagA")
     */
    public function edtTagA(Request $request){
        if (($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) AND ($request->query->has('name') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('name')))) {
            $repo = $this->entityManager->getRepository(Tag::class);
            $id = $request->query->get('id');
            $name = $request->query->get('name');
            $tag = $repo->find($id);
            if($tag === NULL)
            {
                $this->addFlash(
                    'notice',
                    'Impossible de renomer le tag ! Le tag n\'existe pas.'
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }
            if ($this->isGranted('TAG_EDIT', $tag)) {
                try {
                    $this->editCategory($tag, $name);
                    $this->addFlash(
                        'notice',
                        'Tag renomé !'
                    );
                    $flashMessage = $this->get('session')->getFlashBag()->all();

                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $this->addFlash(
                    'notice',
                    "Impossible de renomer le tag ! Vous n'avez pas les autoristaions necessaires"
                );
                $flashMessage = $this->get('session')->getFlashBag()->all();

                return new JsonResponse([false, $flashMessage]);
            }
        }


    }

    private function editCategory(Tag $tag, string $name)
    {
        $tag->setTagName($name);
        $this->entityManager->flush();
    }


}