<?php
declare(strict_types=1);

namespace App\EventSubscriber;


use App\Events\UserCreatedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

class UserCreatedSubscriber implements EventSubscriberInterface
{
    protected MailerInterface $mailer;
    protected string $sender;
    protected string $workspace_name;

    public function __construct(MailerInterface $mailer, string $sender, string $workspace_name)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->workspace_name = $workspace_name;
    }

    public function onUserCreated(UserCreatedEvent $event){

        $user = $event->getUser();
        $email = $user->getEmail();

        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($email)
            ->subject('Приглашение в рабочую площадку!')
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'first_name' => $user->getFirstName(),
                'second_name' => $user->getSecondName(),
                'link' => $event->getLink(),
                'workspace_name' => $this->workspace_name
            ]);

        $this->mailer->send($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreatedEvent::class => 'onUserCreated'
        ];
    }
}