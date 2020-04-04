<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Workspace;
use App\Repository\WorkspaceRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class WorkspaceController extends AbstractController
{
    private const MISSING_DATA_ERROR = "{\"error\" : \"Request MUST contains 'workspace_name', 'workspace_admin_email', 'workspace_admin_password' and 'workspace_ip' parameters!\"}";
    private const VALIDATION_DATA_ERROR = "{\"error\" : \"Request contains INVALID data\"}";
    /**
     * @Route("/workspace", name="workspace_get", methods={"GET"})
     * @return Response
     */
    public function test(WorkspaceRepository $workspaceRepository)
    {
        $workspace = $workspaceRepository->findAll();
        $response = new Response(
            json_encode($workspace)
        );
        //$response->setStatusCode(Response::HTTP_NOT_FOUND);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/workspace", name="create_workspace", methods={"POST"})
     * @return Response
     */
    public function createWorkspace(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $workspaceName = $request->query->get('workspace_name');
        $adminEmail = $request->query->get('workspace_admin_email');
        $adminPassword = $request->query->get('workspace_admin_password');
        $workspaceIP = $request->query->get('workspace_ip');

        $workspace = new Workspace();
        try {
            $workspace->setName($request->query->get('workspace_name'));
            $workspace->setAdminEmail($request->query->get('workspace_admin_email'));
            $workspace->setAdminPassword($request->query->get('workspace_admin_password'));
            $workspace->setIP($request->query->get('workspace_ip'));
        }catch (\TypeError $e) {
            return JsonResponse::fromJsonString(self::MISSING_DATA_ERROR, JsonResponse::HTTP_BAD_REQUEST);
        }
        $errors = $validator->validate($workspace);
        if (count($errors) > 0) {
            return JsonResponse::fromJsonString(self::VALIDATION_DATA_ERROR, JsonResponse::HTTP_BAD_REQUEST);
        }
        $entityManager->persist($workspace);
        $entityManager->flush();
        return JsonResponse::fromJsonString("{\"workspace_id\":\"".$workspace->getId()."\"}");
    }

}