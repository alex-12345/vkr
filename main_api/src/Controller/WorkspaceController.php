<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\WorkspaceRepository;
use App\Response\ApiResponse;
use App\Utils\SettingHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class WorkspaceController extends AbstractController
{
    /**
     * @Route("/api/workspace/info", methods={"GET"})
     */
    public function showWorkspaceInfo(Request $request, SettingHelper $helper, WorkspaceRepository $repository, SerializerInterface $serializer)
    {
        $data = json_decode($request->getContent(), true);
        $errorResponse = $helper::checkApiSecret($data, $this->getParameter('app_secret'));
        if($errorResponse){
            return $errorResponse;
        }
        $workspace = $repository->find(1);
        $response_data = $serializer->normalize($workspace, null, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['id']]);
        return ApiResponse::createSuccessResponse($response_data);
    }

}