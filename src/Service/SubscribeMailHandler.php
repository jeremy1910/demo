<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 02/05/19
 * Time: 17:31
 */

namespace App\Service;




use App\Entity\Token;


class SubscribeMailHandler
{
    private $mailer;
    private $message;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function createSubscribeMail(Token $token)
    {
        $this->message = (new \Swift_Message('Confirmation inscription'))
            ->setFrom('jeremy1910@gmail.com')
            ->setTo($token->getUser()->getAdresseMail())
            ->setBody(
                $this->twig->render(
                // templates/emails/registration.html.twig
                    'subscribe/email.html.twig',
                    ['token' => $token->getToken()]
                ),
                'text/html'
            );

        return $this;
    }

    public function send()
    {
        if ($this->message)
        {
            $this->mailer->send($this->message);
        }
    }

}