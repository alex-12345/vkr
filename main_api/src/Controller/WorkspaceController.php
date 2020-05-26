<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Workspace;
use App\Response\ApiResponse;
use App\Serializer\Normalizer\WorkspaceNormalizer;
use App\Utils\Checker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class WorkspaceController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/api/workspace/info",
     *     tags={"workspace"},
     *     description="check workspace correct installation",
     *     @OA\Parameter(ref="#/components/parameters/workspace_key"),
     *     @OA\Response(response=200, ref="#/components/responses/WorkspaceSetting"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     *
     * @Route("/api/workspace/info", methods={"GET"})
     * @Entity(name="workspace", expr="repository.find(1)")
     */
    public function showWorkspaceInfo(Request $request, ?Workspace $workspace, Checker $checker, WorkspaceNormalizer $serializer)
    {
        $checker->checkAppSecret($request->get("workspace_key"));

        if(is_null($workspace)) throw new NotFoundHttpException('Workspace not found!');

        return ApiResponse::createSuccessResponse($serializer->normalize($workspace));
    }

}