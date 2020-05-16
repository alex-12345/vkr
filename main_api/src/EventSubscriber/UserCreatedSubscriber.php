<?php


namespace App\EventSubscriber;


use App\Events\AppSecretCheckEvent;
use App\Events\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserCreatedSubscriber implements EventSubscriberInterface
{
    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onUserCreated(UserCreatedEvent $event){
        $user = $event->getUser();
        $email = $user->getEmail();
        $first_name = $user->getFirstName();
        $second_name = $user->getSecondName();

        $link = $event->getLink();
        $token = sha1("PRIMER");
        /*
        $email = (new Email())
            ->from($smtpSandbox)
            ->to($email)
            ->subject('Подтверждение регистрации!')
            ->html("<b>$first_name $second_name</b>, для подтверждения регистрации перейдите по следующей ссылке <a href='$link'>$link?token=$token</a>");
        $mailer->send($email);
        */

    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::class => 'onUserCreated'
        ];
    }
}