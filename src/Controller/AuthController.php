<?php

namespace App\Controller;

use App\Entity\Security\ForgottenPassword;
use App\Form\ForgottenPasswordType;
use App\Form\User\Add\UserAddType;
use App\Form\User\ResetPassword\ResetPasswordUserType;
use App\Repository\forgottenPasswordRepository;
use App\Service\Security\ForgottenPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();



        $form = $this->createForm(ForgottenPasswordType::class, null, [
            'action' => $this->generateUrl('forgottenPassword'),
        ]);

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/forgottenPassword", name="forgottenPassword")
     */
    public function forgottenPassword(Request $request, ForgottenPasswordHandler $forgottenPasswordHandler){
        $forgottenPassword = new ForgottenPassword();
        $form = $this->createForm(ForgottenPasswordType::class, $forgottenPassword, [
            'action' => $this->generateUrl('forgottenPassword'),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $forgottenPasswordHandler->add($forgottenPassword);
                $forgottenPasswordHandler->sendMail($forgottenPassword);
                return $this->render('security/forgottenPasswordModalSucces.html.twig');
            }
            catch (\Exception $exception){
                $form->get('email')->addError(new FormError($exception->getMessage()));
            }
        }
        return $this->render('security/forgottenPasswordModal.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgottenPasswordID/{token}", name="forgottenPasswordID")
     */
    public function forgottenPasswordID($token = null, forgottenPasswordRepository $forgottenPasswordRepository, ForgottenPasswordHandler $forgottenPasswordHandler){
        if($token !== null){
            $forgottenPassword = $forgottenPasswordRepository->findOneBy(['hash' => $token]);
            if(!is_null($forgottenPassword)){
                if($forgottenPasswordHandler->validateToken($forgottenPassword)){
                    $user = $forgottenPassword->getUser();
                    $form = $this->createForm(ResetPasswordUserType::class, null, [
                        'action' => $this->generateUrl('resetUser', ['token' => $token])
                    ]);

                    return $this->render('security/resetPasswordPage.html.twig', [
                        'form' => $form->createView(),
                        'formSubmitted' => true,
                    ]);
                }
            }

        }
        return $this->render('security/resetPasswordPage.html.twig', [
            'tokenValid' => false,

        ]);

    }
}
