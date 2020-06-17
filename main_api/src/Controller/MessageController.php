<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class MessageController
{
    /**
     * @OA\Post(
     *     path="/api/messages",
     *     tags={"messages"},
     *     security={{"bearer":{}}},
     *     description="Create new message",
     *     @OA\RequestBody(@OA\JsonContent(@OA\Property(property="name"), type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/messages", methods={"POST"})
     */
    public function createMessage()
    {
        return new Response();

    }
    /**
     * @OA\Get(
     *     path="/api/messages",
     *     tags={"messages"},
     *     security={{"bearer":{}}},
     *     description="Show messages",
     *     @OA\Response(
     *         response=200,
     *         description="Finded messages",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/messages", methods={"GET"})
     */
    public function showMessages()
    {
        return new Response();
    }

    /**
     * @OA\Patch(
     *     path="/api/messages/{id}",
     *     tags={"messages"},
     *     security={{"bearer":{}}},
     *     description="Edit message",
     *     @OA\RequestBody(@OA\JsonContent(@OA\Property(property="name"), type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/messages/{id}", methods={"PATCH"})
     */
    public function editMessage()
    {
        return new Response();
    }
    /**
     * @OA\Delete(
     *     path="/api/messages/{id}",
     *     tags={"messages"},
     *     security={{"bearer":{}}},
     *     description="Remove message",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, description="Success removing project", @OA\JsonContent(@OA\Property(property="empty"))),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/messages/{id<\d+>}", methods={"DELETE"})
     */
    public function removeMessage()
    {
        return new Response();
    }
}