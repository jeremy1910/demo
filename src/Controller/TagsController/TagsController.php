<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 30/05/19
 * Time: 22:19
 */

namespace App\Controller\TagsController;


use App\Entity\Tag;
use App\Form\Tag\TagFilterType;
use App\Service\session\flashMessage\flashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{

    private $entityManager;
    private $flashMessage;

    public function __construct(EntityManagerInterface $entityManager, flashMessage $flashMessage)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashMessage;
    }


    /**
     * @Route("/tag/filter", name="tagFilter")
     */
    public function filter(Request  $request){
        $tagFilter = new Tag\Filter\TagFilter();
        $form = $this->createForm(TagFilterType::class, $tagFilter);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $tabParameterRequest = array_merge(['t' => 'tag'], $tagFilter->iterate());

            return $this->redirectToRoute("get_info", $tabParameterRequest);
        }
        else{

            return new JsonResponse(false, 'formulaire invalide');
        }
    }

    /**
     * @Route("/addTagA", name="addTagA")
     */
    public function addTagA(Request $request){

        if ($request->query->has('name') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('name'))) {
            $tag = new Tag();

            if ($this->isGranted('TAG_CREATE', $tag)) {
                try {
                    $tag->setTagName($request->query->get('name'));
                    $this->createTag($tag);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Tag créé !');
                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    $flashMessage = $this->flashMessage->getFlashMessage('danger', $e->getMessage());
                    return new JsonResponse([false, $flashMessage]);
                }
            } else {

                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Création impossible ! Vous n\'avez pas des autorisations suffisantes');
                return new JsonResponse([false, $flashMessage]);
            }
        }
        else{
            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Création impossible ! Aucun élément à créer');
            return new JsonResponse([false, $flashMessage]);
        }

    }

    private function createTag(Tag $tag){
        $tag->setCreatedUser($this->getUser());
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    /**
     * @Route("/delTagA", name="delTagA")
     */
    public function delTagA(Request $request){
        if ($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) {
            $repo = $this->entityManager->getRepository(Tag::class);
            $id = $request->query->get('id');
            $tag = $repo->find($id);
            if($tag === NULL)
            {
                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible ! Le Tag n\'existe pas.');
                return new JsonResponse([false, $flashMessage]);
            }

            if ($this->isGranted('TAG_DELETE', $tag)) {
                try {
                    $this->deleteTag($tag);

                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Tag supprimé !');

                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible ! Vous n\'avaez pas les autoristaions necessaires');
                return new JsonResponse([false, $flashMessage]);
            }

        }

    }

    private function deleteTag(Tag $tag)
    {

        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }

    /**
     * @Route("/edtTagA", name="edtTagA")
     */
    public function edtTagA(Request $request){
        if (($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) AND ($request->query->has('name') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('name')))) {
            $repo = $this->entityManager->getRepository(Tag::class);
            $id = $request->query->get('id');
            $name = $request->query->get('name');
            $tag = $repo->find($id);
            if($tag === NULL)
            {
                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Impossible de renomer le tag ! Le tag n\'existe pas.');
                return new JsonResponse([false, $flashMessage]);
            }
            if ($this->isGranted('TAG_EDIT', $tag)) {
                try {
                    $this->editCategory($tag, $name);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Tag renomé !');
                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Impossible de renomer le tag ! Vous n\'avez pas les autoristaions necessaires');
                return new JsonResponse([false, $flashMessage]);
            }
        }


    }

    private function editCategory(Tag $tag, string $name)
    {
        $tag->setModifiedUser($this->getUser());
        $tag->setTagName($name);
        $this->entityManager->flush();
    }


}