<?php
declare(strict_types=1);
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class User implements JWTUserInterface
{
    private int $id;
    private array $roles;

    public function __construct($id, array $roles = [])
    {
        $this->id = $id;
        $this->roles = $roles;
    }

    public static function createFromPayload($id, array $payload)
    {
        return new self(
            $id,
            $payload['roles']
        );
    }

    public function getRoles()
    {
        return $this->roles;
    }
    public function getUsername()
    {
        return $this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}