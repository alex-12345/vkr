<?php
declare(strict_types=1);

namespace App\EventSubscriber\EmailNotificationSubscribers;


use App\Events\UserChangesEmailEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserChangesEmailSubscriber extends AbstractEmailNotificationSubscriber implements EventSubscriberInterface
{

    public function onUserChangesEmail(UserChangesEmailEvent $event){

        $user = $event->getUser();
        $email = $user->getEmail();

        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($email)
            ->subject('Подтверждение смены email!')
            ->htmlTemplate('emails/SwitchEmail.html.twig')
            ->context([
                'first_name' => $user->getFirstName(),
                'second_name' => $user->getSecondName(),
                'email' => $email,
                'link' => $event->getLink(),
                'workspace_name' => $this->workspace_name
            ]);

        //$this->mailer->send($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            UserChangesEmailEvent::class => 'onUserChangesEmail'
        ];
    }
}