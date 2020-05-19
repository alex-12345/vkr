<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;

class RefreshedTokenSubscriber implements EventSubscriberInterface
{
    private int $ttl;
    private string $domain;
    private string $cookiePath;

    public function __construct($ttl, $domain, $cookiePath)
    {
        $this->ttl = $ttl;
        $this->cookiePath = $cookiePath;
        $this->domain = $domain;
    }

    public function setRefreshTokenHeader(AuthenticationSuccessEvent $event)
    {
        $refreshToken = $event->getData()['refresh_token'];
        $response = $event->getResponse();
        if($refreshToken){
            $response->headers->setCookie(
                    new Cookie(
                        'REFRESH_TOKEN',
                        $refreshToken,
                        (new \DateTime())->add(new \DateInterval('PT'.$this->ttl.'S')),
                        $this->cookiePath,
                        $this->domain,
                        false
                )
            );
        }

    }
    public static function getSubscribedEvents()
    {
        return [
          'lexik_jwt_authentication.on_authentication_success' => [
              'setRefreshTokenHeader'
          ],
            AuthenticationSuccessEvent::class =>['setRefreshTokenHeader']
        ];
    }
}