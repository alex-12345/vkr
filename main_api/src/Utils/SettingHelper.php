<?php
declare(strict_types=1);

namespace App\Utils;


use App\Response\ApiResponse;

class SettingHelper
{
    public static function checkApiSecret(?array $data, string $workspace_key):?ApiResponse
    {
        if(!isset($data['workspace_key'])){
            return ApiResponse::createFailureResponse("Parameter 'workspace_key' is missing!", ApiResponse::HTTP_BAD_REQUEST);
        };
        if($data['workspace_key'] !== $workspace_key){
            return ApiResponse::createFailureResponse("Parameter 'workspace_key' is not correct!", ApiResponse::HTTP_FORBIDDEN);
        }
        return null;
    }

}