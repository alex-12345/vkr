<?php
declare(strict_types=1);

namespace App\Utils;


class LinkBuilder
{
    private Encryptor $encryptor;

    public function __construct(Encryptor $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    public function getInviteConfirmLink($link, string $email)
    {
        $checkSum = $this->encryptor->computedCheckSim($email);
        $link .= (stristr($link, '?'))? "&": "?";

        return $link."email=".$email."&hash=".$checkSum;
    }

}