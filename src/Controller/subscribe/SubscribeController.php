<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 02/05/19
 * Time: 00:40
 */

namespace App\Controller\subscribe;


use App\Entity\Roles;
use App\Entity\Token;
use App\Entity\User;
use App\Form\SubscribeType;

use App\Repository\RolesRepository;
use App\Repository\TokenRepository;
use App\Security\AuthHandlerAuthenticator;
use App\Service\SubscribeMailHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SubscribeController extends AbstractController
{
    private $em;
    private $tokenRepository;
    private $rolesRepository;

    public function __construct(EntityManagerInterface $em, TokenRepository $tokenRepository, RolesRepository $rolesRepository)
    {
        $this->em = $em;
        $this->tokenRepository = $tokenRepository;
        $this->rolesRepository = $rolesRepository;
    }

    /**
     * @Route("/subscribe", name="subscribe")
     */

    public function subscribe(Request $request, UserPasswordEncoderInterface $passwordEncoder, SubscribeMailHandler $subscribeMailHandler)
    {

        $user = new User();

        $form = $this->createForm(SubscribeType::class, $user);

        $user->setEnable(false);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $role = $this->rolesRepository->findOneBy(['roleName' => 'ROLE_USER']);

            $user->setRoles($role);
            $user->setEnable(false);
            $token = new Token($user);

            $subscribeMailHandler->createSubscribeMail($token)->send();
            $this->em->persist($token);
            $this->em->flush();

            return $this->render('subscribe/subscribe.html.twig', [
               'success' => true,
            ]);

        }

        return $this->render('subscribe/subscribe.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/subscribeValidation/{tokenToValidate}", name="subscribeValidation")
     */
    public function subscribeValidation($tokenToValidate, GuardAuthenticatorHandler $guardAuthenticatorHandler, Request $request, AuthHandlerAuthenticator $authHandlerAuthenticator)
    {


        $token = $this->tokenRepository->findOneBy(['token' => $tokenToValidate]);
        $user = $token->getUser();
        if (!$user->getEnable()) {


            if ($token->isValid()) {
                $user->setEnable(true);
                $this->em->flush();
                $this->addFlash('success', 'Inscription Validée');
                /*
                return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authHandlerAuthenticator,
                    'index'
                );
            */
                return $this->render('subscribe/subscribeSuccess.html.twig');
            }

            $this->em->remove($user);
            $this->em->remove($token);
            $this->em->flush();

            $this->addFlash(
                'danger',
                'Le token est expiré'
            );
        }
        else
        {
            $this->addFlash(
                'notice',
                'utilisateur déja activé.'
            );
        }

        return $this->redirectToRoute('index');

    }

}