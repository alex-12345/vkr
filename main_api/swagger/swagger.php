<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Corporate chat main API",
 *     description="Main api documentation",
 *     version="1.0"
 * )
 * @OA\Server(
 *     url="http://localhost:8000"
 * )
 * @OA\Tag(
 *     name="users",
 *     description="Operations about users",
 * )
 * @OA\Tag(
 *     name="invites",
 *     description="Operations about invites"
 * )
 * @OA\Tag(
 *     name="workspace",
 *     description="Operations about workspace"
 * )
 *
 * @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="Id of resource",
 *     required=true,
 *     @OA\Schema(type="integer")
 * )
 * @OA\SecurityScheme(bearerFormat="JWT", type="apiKey", securityScheme="bearer")
 */