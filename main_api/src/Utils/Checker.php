<?php
declare(strict_types=1);

namespace App\Utils;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Checker
{
    private string $appSecret;
    public function __construct(string $appSecret)
    {
        $this->appSecret = $appSecret;
    }

    public function checkAppSecret(?string $appSecret)
    {
        if($this->appSecret == $appSecret){
            return true;
        }
        throw new AccessDeniedHttpException("Bad 'workspace_key' parameter!");

    }

}