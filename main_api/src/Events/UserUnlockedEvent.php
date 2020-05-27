<?php
declare(strict_types=1);

namespace App\Events;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserUnlockedEvent extends Event
{
    private User $unlockedUser;

    public function __construct(User $unlockedUser)
    {
        $this->unlockedUser = $unlockedUser;
    }

    public function getUnlockedUser(): User
    {
        return $this->unlockedUser;
    }



}