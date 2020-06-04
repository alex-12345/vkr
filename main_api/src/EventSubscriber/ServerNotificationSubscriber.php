<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Events\Project\ProjectCreatedEvent;
use App\Events\Project\ProjectNameUpdateEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ServerNotificationSubscriber  implements EventSubscriberInterface
{
    const DEFAULT_NOTIFICATION_ROLES = ["ROLE_SUPER_ADMIN", "ROLE_ADMIN", "ROLE_MODERATOR"];

    private string $notificationServer;
    private UserRepository $repository;
    private HttpClientInterface $client;
    private string $appSecret;

    public function __construct(string $notificationServer, UserRepository $repository, string $appSecret)
    {
        $this->notificationServer = $notificationServer;
        $this->repository = $repository;
        $this->appSecret = $appSecret;
        $this->client = HttpClient::create();
    }

    public function onProjectCreated(ProjectCreatedEvent $event)
    {
        $project = $event->getProject();
        $mainTopic = $event->getMainTopic();
        $notificationsUserIds = $event->getMembers();
        $payload = [
            'project' => [
                'id' => $project->getId(),
                'name' => $project->getName()
            ],
            'topic' => [
                'id' => $mainTopic->getId(),
                'name' => $mainTopic->getName()
            ]
        ];

        $message = self::createMessage(self::DEFAULT_NOTIFICATION_ROLES, $notificationsUserIds, 'ProjectCreated', $payload);

        self::sendMessages($message);
    }

    public function onProjectNameUpdate(ProjectNameUpdateEvent $event)
    {
        $project = $event->getProject();
        $projectId = $project->getId();
        $notificationsUserIds = $this->repository->findProjectMembersIds($projectId);
        $payload = [
            'id' => $projectId,
            'name' => $project->getName()
        ];

        $message = self::createMessage(self::DEFAULT_NOTIFICATION_ROLES, $notificationsUserIds, 'ProjectNameUpdate', $payload);

        self::sendMessages($message);
    }


    public static function getSubscribedEvents()
    {
        return [
            ProjectCreatedEvent::class => ['onProjectCreated'],
            ProjectNameUpdateEvent::class => ['onProjectNameUpdate']
        ];
    }

    private function createMessage(?array $notificationsRoles, ?array $notificationsUserIds, string $eventName, array $payload):array
    {
        return [
            'notifications_roles' =>  $notificationsRoles,
            'notifications_user_ids' => $notificationsUserIds,
            'output_data' => [
                'event_name' => $eventName,
                'event_time' => (new \DateTime())->format(\DateTimeInterface::ISO8601),
                'payload' => $payload
            ]
        ];
    }
    private function sendMessages(array $message)
    {
        $json_data = json_encode($message);
        $this->client->request("POST", $this->notificationServer, [
            'query' => [
                'token'=> sha1($json_data. $this->appSecret)
            ],
            'body' => ["json_data" => $json_data]
        ]);
    }
}