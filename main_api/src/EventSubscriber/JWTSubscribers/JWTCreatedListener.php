<?php
declare(strict_types=1);

namespace App\EventSubscriber\JWTSubscribers;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="Error403NeedEmailConfirm",
 *     description="need user email confirm",
 *     @OA\JsonContent(
 *          @OA\Property(property="errors",
 *              @OA\Property(property="status", type="integer", example="403"),
 *              @OA\Property(property="message", type="string", example="Email confirmation needed!")
 *          )
 *     )
 * )
 */
class JWTCreatedListener implements EventSubscriberInterface
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
        if(!($user instanceof User)){
            $user = $this->repository->findUserByEmail($user->getUsername());
        }
        if(!$user->getIsActive()){
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