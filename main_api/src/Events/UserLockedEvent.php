<?php
declare(strict_types=1);

namespace App\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserLockedEvent extends Event
{
    private User $lockedUser;

    public function __construct(User $lockedUser)
    {
        $this->lockedUser = $lockedUser;
    }
    public function getLockedUser(): User
    {
        return $this->lockedUser;
    }


}