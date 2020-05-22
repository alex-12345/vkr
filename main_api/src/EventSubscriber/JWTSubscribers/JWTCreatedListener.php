<?php
declare(strict_types=1);

namespace App\EventSubscriber\JWTSubscribers;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class JWTCreatedListener implements EventSubscriberInterface
{

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        if(!$event->getUser()->getIsActive()){
            throw new AccessDeniedHttpException('Email confirmation needed!');
        }

        //TODO check ban
    }

    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => [
                ['onJWTCreated']
            ]
        ];
    }
}