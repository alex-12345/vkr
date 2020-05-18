<?php
declare(strict_types=1);

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public static function createSuccessResponse($data, $meta = null)
    {
        $responseData = ["data" => $data];
        ($meta)? $responseData["meta"] = $meta : null;

        return new static($responseData);
    }

    public static function createFailureResponse($title = null, int $status = 400)
    {
        $data = ["errors" => [
            "status" =>$status
        ]];
        $title ? $data["errors"]["title"] = $title : null;
       return new static($data, $status);
    }

}