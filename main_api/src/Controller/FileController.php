<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class FileController extends AbstractController
{

    /**
     * @OA\Post(
     *     path="/api/files",
     *     tags={"files"},
     *     security={{"bearer":{}}},
     *     description="Create new file",
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
     * @Route("/api/files", methods={"POST"})
     */
    public function createFile()
    {
        return new Response();
    }
    /**
     * @OA\Put(
     *     path="/api/files/{id}",
     *     tags={"files"},
     *     security={{"bearer":{}}},
     *     description="Update file description",
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
     * @Route("/api/files/{id}", methods={"PUT"})
     */
    public function updateFile()
    {
        return new Response();
    }

    /**
     * @OA\Delete(
     *     path="/api/files/{id}",
     *     tags={"files"},
     *     security={{"bearer":{}}},
     *     description="Remove file",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(@OA\Property(property="empty"))),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/files/{id<\d+>}", methods={"DELETE"})
     */
    public function removeFile()
    {
        return new Response();
    }

}