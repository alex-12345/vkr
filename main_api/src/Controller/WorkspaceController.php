<?php
declare(strict_types=1);

namespace App\Controller;

use App\Events\AppSecretCheckEvent;
use App\Repository\WorkspaceRepository;
use App\Response\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class WorkspaceController extends AbstractController
{
    /**
     * @Route("/api/workspace/info", methods={"GET"})
     */
    public function showWorkspaceInfo(Request $request, WorkspaceRepository $repository, SerializerInterface $serializer, EventDispatcherInterface $dispatcher)
    {
        $event = new AppSecretCheckEvent($request->get("workspace_key"));
        $dispatcher->dispatch($event);
        if($event->hasResponse()) return $event->getResponse();

        $workspace = $repository->find(1);
        $response_data = $serializer->normalize($workspace, null, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['id']]);
        return ApiResponse::createSuccessResponse($response_data);
    }

}