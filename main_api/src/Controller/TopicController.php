<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class TopicController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/topics",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Create new topic",
     *     @OA\RequestBody(@OA\JsonContent(@OA\Property(property="name"), type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Topic create completed successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/topics", methods={"POST"})
     */
    public function createTopic()
    {
        return new Response();
    }

    /**
     * @OA\Get(
     *     path="/api/topics/{id}",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Show topic",
     *     @OA\Response(
     *         response=200,
     *         description="Finded topic",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/topics/{id}", methods={"GET"})
     */
    public function showTopic()
    {
        return new Response();
    }

    /**
     * @OA\Get(
     *     path="/api/topics",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Show topics",
     *     @OA\Response(
     *         response=200,
     *         description="Finded topics",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/topics/{id}", methods={"GET"})
     */
    public function showTopics()
    {
        return new Response();
    }

    /**
     * @OA\Put(
     *     path="/api/topics/{id}/name",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Edit topic name",
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
     * @Route("/api/topics/{id}/name", methods={"PUT"})
     */
    public function editTopicName()
    {
        return new Response();
    }

    /**
     * @OA\Put(
     *     path="/api/topics/{id}/project",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Edit topic project",
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
     * @Route("/api/topics/{id}/project", methods={"PUT"})
     */
    public function editTopicProject()
    {
        return new Response();
    }

    /**
     * @OA\Patch(
     *     path="/api/topics/{id}/members",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Edit topic members",
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
     * @Route("/api/topics/{id}/members", methods={"PATCH"})
     */

    public function changeTopicMembers()
    {
        return new Response();
    }

    /**
     * @OA\Patch(
     *     path="/api/topics/{id}/moderators",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Edit topic moderators",
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
     * @Route("/api/topics/{id}/moderators", methods={"PATCH"})
     */
    public function changeTopicModerators()
    {
        return new Response();
    }

    /**
     * @OA\Delete(
     *     path="/api/topics/{id}",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Remove topic",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, description="Success removing project", @OA\JsonContent(@OA\Property(property="empty"))),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/topics/{id<\d+>}", methods={"DELETE"})
     */

    public function removeTopic()
    {
        return new Response();
    }

    /**
     * @OA\Get(
     *     path="/api/topics/{id}/files",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Show topic files",
     *     @OA\Response(
     *         response=200,
     *         description="Finded topic files",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/topics/{id}/files", methods={"GET"})
     */
    public function showAttachFiles()
    {
        return new Response();
    }

    /**
     * @OA\Patch(
     *     path="/api/topics/{id}/files",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Update topic files",
     *     @OA\RequestBody(@OA\JsonContent(@OA\Property(property="name"), type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Topic files list update completed successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/topics/{id<\d+>}/files", methods={"PATCH"})
     */
    public function editAttachFiles()
    {
        return new Response();
    }


}