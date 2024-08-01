<?php

namespace App\Notifications;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;

class Sender
{
    public function __construct(protected MailerInterface $mailer)
    {
        $this->mailer = $mailer;


    }

    public function sendNewUserNotificationToAdmin(UserInterface $user): void
    {
        $message = (new Email())
            ->from('account@bucket-list.com')
            ->to('admin@bucket-list.com')
            ->subject('Nouvel utilisateur inscrit')
            ->text('Un nouvel utilisateur s\'est inscrit sur votre site.')
            ->html('<p>Un nouvel utilisateur s\'est inscrit sur votre site.</p>
                    <p>Voici son adresse email : ' . $user->getEmail() . '</p>');
        $this->mailer->send($message);
    }

    private function html(string $string)
    {
    }
}