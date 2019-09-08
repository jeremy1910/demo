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
use App\Repository\forgottenPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ForgottenPasswordHandler
{

    private $userRepository;
    private $entityManager;
    private $swift_Mailer;
    private $twig_Environment;
    private $forgottenPasswordRepository;


    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, \Swift_Mailer $swift_Mailer, Environment $twig_Environment, forgottenPasswordRepository $forgottenPasswordRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->swift_Mailer = $swift_Mailer;
        $this->twig_Environment = $twig_Environment;
        $this->forgottenPasswordRepository = $forgottenPasswordRepository;
    }

    public function add(ForgottenPassword $forgottenPassword){


        $user = $this->userRepository->findOneBy(['adresseMail' => $forgottenPassword->getEmail()]);
        if($user === null){
            throw new \Exception("L'adresse mail ne correspond à aucun utlisteur connu");
        }
        $doublon = $this->forgottenPasswordRepository->findOneBy(['user' => $user]);
        if($doublon !== null){
            if($this->validateToken($doublon)){
                throw new \Exception("Une demande de réinitialisation est dejà en cours pour cette adresse mail, merci de valider cette demande ou d'attendre 6h pour en faire une nouvelle");
            }
            else{

                $this->entityManager->remove($doublon);
                $this->entityManager->flush();
                $this->entityManager->clear();

            }

        }

        $forgottenPassword->setHash(uniqid());
        $forgottenPassword->setCreatedAt(new \DateTime());
        $forgottenPassword->setUser($user);

        $this->entityManager->merge($forgottenPassword);

        $this->entityManager->flush();


    }



    public function sendMail(ForgottenPassword $forgottenPassword){
        try {
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('jerambaud05@gmail.com')
                ->setTo($forgottenPassword->getUser()->getAdresseMail())
                ->setBody($this->twig_Environment->render('security/forgottenPasswordEmail.html.twig', [
                        'token' => $forgottenPassword->getHash(),
                        'user' => $forgottenPassword->getUser(),
                    ]
                ), 'text/html');
            $this->swift_Mailer->send($message);
        }catch (\Exception $e){
            $this->entityManager->remove($forgottenPassword);
            $this->entityManager->flush();
            throw new \Exception("L'email n'a pas pu être envoyé, merci de revenir plus tard ...");
        }
        return new Response('toto');
    }

    public function validateToken(ForgottenPassword $forgottenPassword){

        $interval = new \DateInterval('PT6H');
        return $forgottenPassword->getCreatedAt()->add($interval) > new \DateTime();
    }

}