<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Response\ApiResponse;
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

    public function convertJsonStringToArray(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (in_array($request->getMethod(),['GET','DELETE']) || $request->getContentType() != 'json' || !$request->getContent()) {
            return ;
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $event->setResponse(ApiResponse::createFailureResponse("Invalid json body", ApiResponse::HTTP_BAD_REQUEST));
            return ;
        }
        $request->request->add(is_array($data) ? $data : []);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['setRefreshTokenHeader', 0],
                ['convertJsonStringToArray', -100]
            ]
        ];
    }
}