<?php
declare(strict_types=1);

namespace App\Utils;


class Encryptor
{
    public string $appSecretKey;

    public function __construct(string $AppSecretKey)
    {
        $this->appSecretKey = $AppSecretKey;
    }

    public function computedCheckSim(array $payload)
    {
        return sha1(implode($payload,"_").$this->appSecretKey);
    }

}