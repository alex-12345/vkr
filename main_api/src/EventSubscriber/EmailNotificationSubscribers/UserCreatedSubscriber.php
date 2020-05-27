<?php
declare(strict_types=1);

namespace App\EventSubscriber\EmailNotificationSubscribers;


use App\Events\UserCreatedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserCreatedSubscriber extends AbstractEmailNotificationSubscriber implements EventSubscriberInterface
{

    public function onUserCreated(UserCreatedEvent $event){

        $user = $event->getUser();
        $email = $user->getEmail();

        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($email)
            ->subject('Приглашение в рабочую площадку!')
            ->htmlTemplate('emails/SignUp.html.twig')
            ->context([
                'first_name' => $user->getFirstName(),
                'second_name' => $user->getSecondName(),
                'link' => $event->getLink(),
                'workspace_name' => $this->workspace_name
            ]);
        print($event->getLink());
        //$this->mailer->send($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::class => 'onUserCreated'
        ];
    }
}