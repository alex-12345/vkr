<?php
declare(strict_types=1);
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class User implements JWTUserInterface
{
    private string $email;
    private array $roles;

    public function __construct($email, array $roles = [])
    {
        $this->email = $email;
        $this->roles = $roles;
    }

    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload['roles']
        );
    }

    public function getRoles()
    {
        return $this->roles;
    }
    public function getUsername()
    {
        return $this->email;
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