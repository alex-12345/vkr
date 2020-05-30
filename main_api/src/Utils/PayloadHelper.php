<?php
declare(strict_types=1);

namespace App\Utils;

use App\Entity\User;

class PayloadHelper
{
    public function getPayloadForRecovery(User $user): array
    {
        return [
            "id" => $user->getId(),
            "current_password" => $user->getPassword()
        ];
    }
}
