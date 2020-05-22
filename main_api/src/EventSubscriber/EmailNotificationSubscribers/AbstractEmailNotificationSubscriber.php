<?php
declare(strict_types=1);

namespace App\EventSubscriber\EmailNotificationSubscribers;


use Symfony\Component\Mailer\MailerInterface;

abstract class AbstractEmailNotificationSubscriber
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
}