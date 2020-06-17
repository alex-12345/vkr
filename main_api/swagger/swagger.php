<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Corporate messenger main API",
 *     description="Main api documentation",
 *     version="1.0"
 * )
 * @OA\Server(
 *     url="http://sapechat.ru"
 * )
 * @OA\Server(
 *     url="http://localhost:8010"
 * )
 * @OA\Tag(
 *     name="auth",
 *     description="Operations about auth",
 * )
 * @OA\Tag(
 *     name="workspace",
 *     description="Operations about workspace"
 * )
 * @OA\Tag(
 *     name="invites",
 *     description="Operations about invites"
 * )
 * @OA\Tag(
 *     name="users",
 *     description="Operations about users",
 * )
 * @OA\Tag(
 *     name="recovery",
 *     description="Operations about recovery"
 * )
 * @OA\Tag(
 *     name="projects",
 *     description="Operations about projects"
 * )
 * @OA\Tag(
 *     name="topics",
 *     description="Operations about topics"
 * )
 * @OA\Tag(
 *     name="messages",
 *     description="Operations about messages"
 * )
 * @OA\Tag(
 *     name="files",
 *     description="Operations about files"
 * )
 * @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="Id of resource",
 *     required=true,
 *     @OA\Schema(type="integer")
 * )
 * @OA\SecurityScheme(bearerFormat="JWT", type="http", scheme="bearer", securityScheme="bearer")
 *
 * @OA\Response(
 *     response="Error401JWT",
 *     description="Invalid credentials",
 *     @OA\JsonContent(
 *          @OA\Property(property="code", type="integer", example="401"),
 *          @OA\Property(property="message", type="string")
 *     )
 * )
 * @OA\Response(
 *     response="SuccessJWT",
 *     description="success JWT login",
 *     @OA\Header(
 *        header="Set-Cookie",
 *        @OA\Schema(
 *           type="string",
 *           example="REFRESH_TOKEN=8df83380c7fb49aa0a60f2b40c42bda63222e96f57b68e150f8893856867c9d607068cd4116863f0cd95c91730b00e2061f88e1b845767ca85ed8c15a072c0eb; expires=Wed, 24-Jun-2020 19:41:42 GMT; Max-Age=2592000; path=/api/auth/token/refresh; domain=http://localhost:8000; httponly; samesite=lax"
 *        )
 *     ),
 *     @OA\JsonContent(
 *           @OA\Property(property="token", type="string"),
 *           @OA\Property(property="refresh_token", type="string")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/auth/login_check",
 *     tags={"auth"},
 *     description="login in workspace",
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="username", type="string", example="johndoe@mail.com"),
 *              @OA\Property(property="password", type="string", example="rbrbvjhf")
 *          )
 *      ),
 *      @OA\Response(response=200, ref="#/components/responses/SuccessJWT"),
 *      @OA\Response(response=400, ref="#/components/responses/Error400"),
 *      @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
 *      @OA\Response(response=403, ref="#/components/responses/Error403NeedEmailConfirm"),
 *      @OA\Response(response=423, ref="#/components/responses/Error423")
 * )
 * @OA\Post(
 *     path="/api/auth/token/refresh",
 *     tags={"auth"},
 *     description="refresh JWT token",
 *     @OA\Parameter(name="REFRESH_TOKEN",in="cookie", @OA\Schema(type="string")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="refresh_token", type="string"))),
 *     @OA\Response(response=200, ref="#/components/responses/SuccessJWT"),
 *     @OA\Response(response=400, ref="#/components/responses/Error400"),
 *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
 *     @OA\Response(response=403, ref="#/components/responses/Error403NeedEmailConfirm"),
 *     @OA\Response(response=423, ref="#/components/responses/Error423")
 *
 * )
 *
 */