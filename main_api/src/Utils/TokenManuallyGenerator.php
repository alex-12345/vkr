<?php
declare(strict_types=1);

namespace App\Utils;


use App\Entity\User;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TokenManuallyGenerator
{
    private int $ttl;
    private JWTTokenManagerInterface $JWTManager;
    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(int $ttl, JWTTokenManagerInterface $JWTManager, RefreshTokenManagerInterface $refreshTokenManager, EventDispatcherInterface $dispatcher)
    {
        $this->ttl = $ttl;
        $this->JWTManager = $JWTManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->dispatcher =$dispatcher;
    }

    public function JWTResponseGenerate(User $confirmedUser):JsonResponse
    {
        //TODO refactor all this via dispatch jwt library events
        $jwt = $this->JWTManager->create($confirmedUser);

        $refreshToken = $this->refreshTokenManager->create();
        $refreshToken->setUsername($confirmedUser->getUsername());
        $refreshToken->setRefreshToken();
        $refreshToken->setValid((new \DateTime())->add(new \DateInterval('PT'.$this->ttl.'S')));
        $this->refreshTokenManager->save($refreshToken);
        $refreshToken= $refreshToken->getRefreshToken();

        $response = new JWTAuthenticationSuccessResponse($jwt, ["refresh_token" =>$refreshToken]);

        $event = new AuthenticationSuccessEvent(['token' => $jwt, "refresh_token" =>$refreshToken], $confirmedUser, $response);
        $this->dispatcher->dispatch($event);

        $response->setData($event->getData());
        return $response;

    }

}