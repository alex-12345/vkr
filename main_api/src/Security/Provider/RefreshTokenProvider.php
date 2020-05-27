<?php
declare(strict_types=1);

namespace App\Security\Provider;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RefreshTokenProvider implements UserProviderInterface
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function loadUserByUsername($id)
    {
        $user = $this->repository->find($id);

        if($user instanceof User)
            return $user;

        throw new NotFoundHttpException("User for this refresh token was not founded!");
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass(string $class)
    {
        return User::class === $class;
    }
}