<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 03/09/19
 * Time: 22:56
 */

namespace App\Service\Security;


use App\Entity\Security\ForgottenPassword;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class ForgottenPasswordHandler
{

    private $userRepository;
    private $entityManager;
    private $swift_Mailer;
    private $twig_Environment;


    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, \Swift_Mailer $swift_Mailer, Environment $twig_Environment)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->swift_Mailer = $swift_Mailer;
        $this->twig_Environment = $twig_Environment;
    }

    public function add(ForgottenPassword $forgottenPassword){

        /**
         * @var $user User[]
         */
        $user = $this->userRepository->findBy(['adresseMail' => $forgottenPassword->getEmail()]);
        if(count($user) == 1){

            $forgottenPassword->setHash(uniqid());
            $forgottenPassword->setCreatedAt(new \DateTime());
            $forgottenPassword->setUser($user[0]);

            $this->entityManager->persist($forgottenPassword);
            $this->entityManager->flush();
        }else if(count($user) > 1){
            throw new \Exception("Plusieur utilisateur ont cette adresse mail, merci de contacter l'administrateur");
        }else{
            throw new \Exception("L'adresse mail ne correspond à aucun utlisteur connu");
        }

    }

    public function sendMail(ForgottenPassword $forgottenPassword){
        $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('jeremy1910@gmail.com')
            ->setTo($forgottenPassword->getUser()->getAdresseMail())
            ->setBody($this->twig_Environment->render('security/forgottenPasswordEmail.html.twig', [
                        'token' => $forgottenPassword->getHash(),
                        'user' => $forgottenPassword->getUser(),
                    ]
            ), 'text/html');

        $this->swift_Mailer->send($message);

        dd($message);
    }

}