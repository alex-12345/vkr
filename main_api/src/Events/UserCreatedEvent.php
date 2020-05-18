<?php
declare(strict_types=1);

namespace App\Events;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserCreatedEvent extends Event
{
    protected User $user;
    protected string $link;

    public function __construct(User $user, string $link)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}