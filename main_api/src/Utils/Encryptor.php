<?php
declare(strict_types=1);

namespace App\Utils;


class Encryptor
{
    public string $appSecret;

    public function __construct(string $appSecret)
    {
        $this->appSecret = $appSecret;
    }

    public function computedCheckSim(array $payload)
    {
        return sha1(implode($payload,"_").$this->appSecret);
    }

}