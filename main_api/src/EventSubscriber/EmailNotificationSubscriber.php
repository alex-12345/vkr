<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Events\RecoveryCreatedEvent;
use App\Events\UserChangesEmailEvent;
use App\Events\UserCreatedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailNotificationSubscriber  implements EventSubscriberInterface
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

    public function onUserChangesEmail(UserChangesEmailEvent $event)
    {
        $user = $event->getUser();
        $email = $user->getEmail();
        $context = [
            'first_name' => $user->getFirstName(),
            'second_name' => $user->getSecondName(),
            'email' => $email,
            'link' => $event->getLink(),
            'workspace_name' => $this->workspace_name
        ];
        self::sendEmail($email, 'Подтверждение смены email!', 'emails/SwitchEmail.html.twig', $context);
    }

    public function onUserCreated(UserCreatedEvent $event)
    {
        $user = $event->getUser();
        $context = [
            'first_name' => $user->getFirstName(),
            'second_name' => $user->getSecondName(),
            'link' => $event->getLink(),
            'workspace_name' => $this->workspace_name
        ];
        self::sendEmail($user->getEmail(), 'Приглашение в рабочую площадку!', 'emails/SignUp.html.twig', $context);
    }

    public function onRecoveryCreated(RecoveryCreatedEvent $event)
    {
        $user = $event->getUser();
        $context = [
            'first_name' => $user->getFirstName(),
            'second_name' => $user->getSecondName(),
            'link' => $event->getLink(),
            'workspace_name' => $this->workspace_name
        ];
        self::sendEmail($user->getEmail(), 'Восстановление пароля!', 'emails/PasswordRecovery.html.twig', $context);
    }

    private function sendEmail(string $email, string $subject, string $template, array $context)
    {
        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($email)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);
        //var_dump($context);

        $this->mailer->send($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            UserChangesEmailEvent::class => 'onUserChangesEmail',
            UserCreatedEvent::class => 'onUserCreated',
            RecoveryCreatedEvent::class => 'onRecoveryCreated'
        ];
    }
}