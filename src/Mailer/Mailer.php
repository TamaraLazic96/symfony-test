<?php

namespace App\Mailer;

use App\Entity\User;
use Twig\Environment;

class Mailer
{
    private $mailer;
    private $twig;
    private $mailFrom;

    public function __construct(\Swift_Mailer $mailer, Environment $twig, string $mailFrom)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->mailFrom = $mailFrom;
    }

    public function sendConfirmationMail(User $user)
    {
        $body = $this->twig->render('email/registration.html.twig', [
            'user' => $user
        ]);
        $message = (new \Swift_Message())
            ->setSubject('Welcome to the micro post app!')
            ->setFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}