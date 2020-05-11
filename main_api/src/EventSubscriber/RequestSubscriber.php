<?php
declare(strict_types=1);

namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestSubscriber implements EventSubscriberInterface
{
    public function setRefreshTokenHeader(RequestEvent $event){
        $request = $event->getRequest();
        if($request->getPathInfo() === '/api/auth/token/refresh' && $request->cookies->has('REFRESH_TOKEN')) {
            $request->attributes->set('refresh_token', $request->cookies->get('REFRESH_TOKEN'));
        }
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                'setRefreshTokenHeader'
            ]
        ];
    }
}