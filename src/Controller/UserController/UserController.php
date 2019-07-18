<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 26/06/19
 * Time: 21:31
 */

namespace App\Controller\UserController;


use App\Entity\User;
use App\Form\User\Filter\UserFilterType;
use App\Service\session\flashMessage\flashMessage;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $entityManager;
    private $flashMessage;

    public function __construct(EntityManagerInterface $entityManager, flashMessage $flashMessage)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashMessage;
    }


    /**
     * @Route("/user/filter", name="userFilter")
     */
    public function filter(Request  $request){
        $userFilter = new User\Filter\UserFilter();
        $form = $this->createForm(UserFilterType::class, $userFilter);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $tabParameterRequest = array_merge(['t' => 'user'], $userFilter->iterate());
            //dd($tabParameterRequest);
            return $this->redirectToRoute("get_info", $tabParameterRequest);
        }
        else{

            return new JsonResponse([false, 'formulaire invalide']);
        }
    }

    /**
     * @Route("/addUserA", name="addUserA")
     */
    public function addUserA(Request $request){

        if ($request->query->has('name') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('name'))) {
            $user = new User();

            if ($this->isGranted('TAG_CREATE', $user)) {
                try {
                    $user->setUserName($request->query->get('name'));
                    $this->createUser($user);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'User créé !');
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

    private function createUser(User $user){
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @Route("/delUserA", name="delUserA")
     */
    public function delUserA(Request $request){
        if ($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) {
            $repo = $this->entityManager->getRepository(User::class);
            $id = $request->query->get('id');
            $user = $repo->find($id);
            if($user === NULL)
            {
                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible ! Le User n\'existe pas.');
                return new JsonResponse([false, $flashMessage]);
            }

            if ($this->isGranted('TAG_DELETE', $user)) {
                try {
                    $this->deleteCategory($user);

                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'User supprimé !');

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

    private function deleteCategory(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @Route("/edtUserA", name="edtUserA")
     */
    public function edtUserA(Request $request){
        if (($request->query->has('id') AND \preg_match("/^[0-9]+$/", $request->query->get('id'))) AND ($request->query->has('name') AND \preg_match("/[A-Za-z0-9]+/", $request->query->get('name')))) {
            $repo = $this->entityManager->getRepository(User::class);
            $id = $request->query->get('id');
            $name = $request->query->get('name');
            $user = $repo->find($id);
            if($user === NULL)
            {
                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Impossible de renomer le user ! Le user n\'existe pas.');
                return new JsonResponse([false, $flashMessage]);
            }
            if ($this->isGranted('TAG_EDIT', $user)) {
                try {
                    $this->editCategory($user, $name);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'User renomé !');
                    return new JsonResponse([true, $flashMessage]);
                }catch (\Exception $e){
                    return new JsonResponse($e->getMessage());
                }
            } else {

                $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Impossible de renomer le user ! Vous n\'avez pas les autoristaions necessaires');
                return new JsonResponse([false, $flashMessage]);
            }
        }


    }

    private function editCategory(User $user, string $name)
    {
        $user->setUserName($name);
        $this->entityManager->flush();
    }
}