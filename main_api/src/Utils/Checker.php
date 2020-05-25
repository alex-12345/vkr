<?php
declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use OpenApi\Annotations as OA;

/**
 * @OA\Parameter(
 *     name="workspace_key",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 */

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