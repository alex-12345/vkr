<?php
declare(strict_types=1);

namespace App\Utils;


use App\Entity\User;

class LinkBuilder
{
    private Encryptor $encryptor;

    public function __construct(Encryptor $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    public function getInviteConfirmLink($link, array $arguments)
    {
        $checkSum = $this->encryptor->computedCheckSim($arguments);
        $link .= (stristr($link, '?'))? "&": "?";
        if(isset($arguments['email'])) {
            unset($arguments['email']);
        }
        return $link.http_build_query($arguments)."&hash=".$checkSum;
    }

    public function computeEmailConfirmPayload(User $user, bool $isAdmin):array
    {
        return $this->encryptor->computeEmailConfirmPayload($user,$isAdmin);
    }

}