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

    public function getInviteConfirmLink($link, array $arguments)
    {
        $checkSum = $this->encryptor->computedCheckSim($arguments);
        $link .= (stristr($link, '?'))? "&": "?";

        return $link.http_build_query($arguments)."&hash=".$checkSum;
    }

}