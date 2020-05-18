<?php


namespace App\EventSubscriber;


use App\Events\AppSecretCheckEvent;
use App\Response\ApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppSecretCheckSubscriber implements EventSubscriberInterface
{
    protected ?string $AppSecretKey;

    public function __construct(string $AppSecretKey)
    {
        $this->AppSecretKey = $AppSecretKey;
    }

    public function onAppSecretCheck(AppSecretCheckEvent $event): void
    {
        if($event->getAppUserKey() === $this->AppSecretKey){
            return ;
        }
        $event->setResponse(
            ApiResponse::createFailureResponse("Bad or missing 'workspace_key' parameter!", ApiResponse::HTTP_FORBIDDEN)
        );

    }
    public static function getSubscribedEvents()
    {
        return [
            AppSecretCheckEvent::class => 'onAppSecretCheck'
        ];
    }
}