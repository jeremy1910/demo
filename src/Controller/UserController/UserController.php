<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 26/06/19
 * Time: 21:31
 */

namespace App\Controller\UserController;


use App\Entity\Roles;
use App\Entity\User;
use App\Form\User\Add\UserAddType;
use App\Form\User\Filter\UserFilterType;
use App\Form\User\ResetPassword\ResetPasswordUserType;
use App\Form\UserType;
use App\Repository\forgottenPasswordRepository;
use App\Repository\RolesRepository;
use App\Service\Security\ForgottenPasswordHandler;
use App\Service\session\flashMessage\flashMessage;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private $entityManager;
    private $flashMessage;
    private $rolesRepository;
    private $passwordEncoder;


    public function __construct(EntityManagerInterface $entityManager, flashMessage $flashMessage, RolesRepository $rolesRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashMessage;
        $this->rolesRepository = $rolesRepository;
        $this->passwordEncoder = $passwordEncoder;
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
    public function addUserA(Request $request, ValidatorInterface $validator){
        $user = new User();
        if ($this->isGranted('USER_CREATE', $user)) {

            $form = $this->createForm(UserAddType::class, $user, [
                'action' => $this->generateUrl("addUserA")
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                try {
                    $user->setPassword($this->encodePasswordFixture($user, $user->getPassword()));
                    $this->createUser($user);
                    $flashMessage = $this->flashMessage->getFlashMessage('success', 'Utilisateur créé avec succès !');
                    return new JsonResponse([true, $flashMessage]);
                } catch (\Exception $e) {
                    $flashMessage = $this->flashMessage->getFlashMessage('danger', $e->getMessage());
                    return new JsonResponse([false, $flashMessage]);
                }

            } else {

                return $this->render('user/addUser.html.twig', array(
                    'formUserAdd' => $form->createView(),
                ));
            }
        } else {

            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Création impossible ! Vous n\'avez pas des autorisations suffisantes');
            return new JsonResponse([false, $flashMessage]);
        }

    }
    private function encodePasswordFixture(User $user, string $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }

    private function createUser(User $user){
        $user->setCreatedUser($this->getUser());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @Route("/delUserA/{id}", name="delUserA")
     */
    public function delUserA(User $user, Request $request){

        if($user === NULL)
        {
            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible ! L\'utilisateur n\'existe pas.');
            return new JsonResponse([false, $flashMessage]);
        }
        if($user->getId() == 1 OR $user->getUsername() === 'admin'){
            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'L\'utilisateur admin ne peut pas être supprimé !');
            return new JsonResponse([false, $flashMessage]);
        }
        if ($this->isGranted('USER_DELETE', $user)) {
            try {
                $this->deleteCategory($user);

                $flashMessage = $this->flashMessage->getFlashMessage('success', 'Utilisateur supprimé !');

                return new JsonResponse([true, $flashMessage]);
            }catch (\Exception $e){
                return new JsonResponse($e->getMessage());
            }
        } else {

            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Suppression impossible ! Vous n\'avaez pas les autoristaions necessaires');
            return new JsonResponse([false, $flashMessage]);
        }



    }

    private function deleteCategory(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * @Route("/edtUserA/{id}", name="edtUserA", defaults={"id"=null})
     */
    public function edtUserA(User $user, Request $request)
    {

        if ($this->isGranted('USER_EDIT', $user)) {
            $form = $this->createForm(UserAddType::class, $user, [
                'selected_user' => $user->getId(),
                'action' => $this->generateUrl('edtUserA', ['id' => $user->getId()])
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setModifiedAt(new \DateTime());
                $this->editUser($user);

                $flashMessage = $this->flashMessage->getFlashMessage('success', 'Utilisateur modifié');
                return new JsonResponse([true, $flashMessage]);

            } else {

                return $this->render('user/edtUser.html.twig', array(
                    'formUserAdd' => $form->createView(),
                ));
            }
        } else {

            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Modification impossible ! Vous n\'avez pas des autorisations suffisantes');
            return new JsonResponse([false, $flashMessage]);
        }

    }

    /**
     * @Route("/resetUserA/{id}", name="resetUserA")
     */
    public function resetUserA(User $user, Request $request){

        if($this->canEditUser($user)){
            $form = $this->createForm(ResetPasswordUserType::class, null, [
                'action' => $this->generateUrl('resetUserA', ['id' => $user->getId()])
            ]);

            if ($this->formResetPasswordIsValide($form, $request)) {
                $data = $form->getData();
                $this->doChangePassword($data['password'], $user);
                $flashMessage = $this->flashMessage->getFlashMessage('success', 'Utilisateur modifié');
                return new JsonResponse([true, $flashMessage]);
            } else {
                return $this->render('user/resetUser.html.twig', array(
                    'formUserAdd' => $form->createView(),
                ));
            }
        }else{
            $flashMessage = $this->flashMessage->getFlashMessage('danger', 'Modification impossible ! Vous n\'avez pas des autorisations suffisantes');
            return new JsonResponse([false, $flashMessage]);
        }
    }

    /**
     * @Route("/resetUserByToken/{token}", name="resetUser")
     */
    public function resetUserPasswordByToken(string $token, Request $request, forgottenPasswordRepository $forgottenPasswordRepository, ForgottenPasswordHandler $forgottenPasswordHandler){

        $form = $this->createForm(ResetPasswordUserType::class, null, [
            'action' => $this->generateUrl('resetUser', ['token' => $token])
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            if($form->isValid()) {

                $forgottenPassword = $forgottenPasswordRepository->findOneBy(['hash' => $token]);
                $user = $forgottenPassword->getUser();

                if($forgottenPasswordHandler->validateToken($forgottenPassword)) {
                    $data = $form->getData();

                    $this->doChangePassword($data['password'], $user);
                    $this->entityManager->remove($forgottenPassword);
                    $this->entityManager->flush();
                    $this->flashMessage->createFlashMessage('success', 'Utilisateur modifié');
                    return $this->render('security/resetPasswordPage.html.twig', array(
                        'resetSuccess' => true,
                    ));
                }else{

                    return $this->render('security/resetPasswordPage.html.twig', array(
                        'tokenValid' => false,
                    ));
                }

            }else{

                return $this->render('security/resetPasswordPage.html.twig', array(
                    'form' => $form->createView(),
                    'formValid' => false,
                    'formSubmitted' => true,

                ));
            }


        } else {

            return $this->render('security/resetPasswordPage.html.twig', array(
                'formSubmitted' => false,
            ));
        }

    }

    /**
     * @Route("/user/changeEmailA/{id}", name="changeEmailA")
     */
    public function changeEmailA(User $user, Request $request){
        if($this->canEditUser($user)){
            $form = $this->createForm(UserAddType::class, $user, [
                'action' => $this->generateUrl('changeEmailA', ['id' => $user->getId()])
            ]);
            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid()){
                try{
                    $this->doChangeMail($user->getAdresseMail(), $user);

                }catch (\Exception $exception){

                    $form->get('adresseMail')->addError(new FormError('Une erreur est survenu'));

                    return $this->render('user/changeUserMail.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $flashMessage = $this->flashMessage->getFlashMessage('success', 'Utilisateur modifié');
                return new JsonResponse([true, $flashMessage]);
            }else{

                return $this->render('user/changeUserMail.html.twig', [
                   'form' => $form->createView(),
                ]);
            }
        }
        else{
            $flashMessage = $this->flashMessage->getFlashMessage('error', "Vous ne pouvez effectuer cette action.");
            return new JsonResponse([false, $flashMessage]);
        }
    }

    private function doChangeMail($mail, User $user){
        $user->setAdresseMail($mail);

        $this->entityManager->flush();
    }
    private function doChangePassword(string $password, User $user){

        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $this->entityManager->flush();
    }

    private function formResetPasswordIsValide(FormInterface $form, Request $request){
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            return true;
        } else {
            return false;
        }
    }

    private function canEditUser(User $user){
        if($this->isGranted('USER_EDIT', $user)){
            return true;
        }
        else{
            return false;
        }
    }

    private function editUser(User $user)
    {
        $user->setModifiedUser($this->getUser());

        $this->entityManager->flush();
    }
}