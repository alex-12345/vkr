<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\WorkspaceRepository;
use App\Response\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class WorkspaceController extends AbstractController
{
    /**
     * @Route("/api/workspace/info", methods={"GET"})
     */
    public function showWorkspaceInfo(Request $request, WorkspaceRepository $repository, SerializerInterface $serializer)
    {
        $workspace_key = $this->getParameter('app_secret');
        $data = json_decode($request->getContent(), true);
        if(!isset($data['workspace_key'])){
            return ApiResponse::createFailureResponse("Parameter 'workspace_key' is missing!", ApiResponse::HTTP_BAD_REQUEST);
        };
        if($data['workspace_key'] !== $workspace_key){
            return ApiResponse::createFailureResponse("Parameter 'workspace_key' is not correct!", ApiResponse::HTTP_FORBIDDEN);
        }
        $workspace = $repository->find(1);
        $workspace = $serializer->normalize($workspace);
        return ApiResponse::createSuccessResponse($workspace);



    }

}