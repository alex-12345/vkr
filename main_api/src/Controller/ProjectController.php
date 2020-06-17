<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\Topic;
use App\Entity\TopicUser;
use App\Entity\User;
use App\Events\Project\ProjectCreatedEvent;
use App\Events\Project\ProjectNameUpdateEvent;
use App\Form\Project\ProjectType;
use App\Form\Project\UpdateProjectNameType;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Serializer\Normalizer\ProjectNormalizer;
use App\Serializer\Normalizer\ProjectUserNormalizer;
use App\Serializer\Normalizer\TopicNormalizer;
use App\Utils\PaginationHelper;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class ProjectController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/projects",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Create new project",
     *     @OA\RequestBody(ref="#/components/requestBodies/NewProject"),
     *     @OA\Response(
     *         response=200,
     *         description="Project creation completed successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="data",
     *                  @OA\Property(property="project", type="object", ref="#/components/schemas/ProjectBase"),
     *                  @OA\Property(property="main_topic",type="object", ref="#/components/schemas/TopicBase")
     *              )
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/projects", methods={"POST"})
     * @Security("is_granted('ROLE_MODERATOR')")
     */
    public function createProject(Request $request, UserRepository $userRepository, ProjectNormalizer $projectNormalizer, TopicNormalizer $topicNormalizer, EventDispatcherInterface $dispatcher)
    {
        $form = $this->createForm(ProjectType::class, null, ['main_topic'=>true]);
        if($form->submit($request->request->all())->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $creator = $em->getReference(User::class, $this->getUser()->getId());

            $project = new Project();
            $project->setCreator($creator);
            $project->setName($form->get('name')->getData());
            $em->persist($project);

            $mainTopic = new Topic(true);
            $mainTopic->setName($form->get('main_topic_name')->getData());
            $mainTopic->setCreator($creator);
            $mainTopic->setProject($project);
            $em->persist($mainTopic);

            $members = $userRepository->findActiveUsersByIdsAndRoles($form->get('member_ids')->getData(), User::ROLE_USER);

            foreach ($members as $member){
                $user = $em->getReference(User::class, $member);
                $em->persist(new ProjectUser($user, $project));
                $em->persist(new TopicUser($user, $mainTopic));
            }

            $em->flush();

            $dispatcher->dispatch(new ProjectCreatedEvent($project, $mainTopic, $members));
            return ApiResponse::createSuccessResponse(
                [
                    'project' => $projectNormalizer->normalize($project),
                    'main_topic' => $topicNormalizer->normalize($mainTopic)
                ]
            );
        }
        throw new BadRequestHttpException('Bad content');

    }
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     tags={"projects"},
     *     description="Show projects",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="page[size]", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="page[number]", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, ref="#/components/responses/ProjectCollection"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     * )
     * @Route("/api/projects", methods={"GET"})
     */
    public function getProjects(Request $request, PaginationHelper $paginationHelper, ProjectNormalizer $normalizer)
    {
        $pageParams = $paginationHelper->getPageAndSize($request);

        $transferredUser = ($this->isGranted("ROLE_MODERATOR"))? null: $this->getUser();

        $paginator = $this->getDoctrine()->getRepository(Project::class)->findProjects($pageParams->getFirstResultNumber(), $pageParams->getMaxResultNumber(), $transferredUser);
        $projectsAmount = count($paginator);

        if($projectsAmount){
            return ApiResponse::createSuccessResponse($paginationHelper->paginate($paginator, $normalizer), ['count'=> $projectsAmount]);
        }
        throw new NotFoundHttpException('Projects not found!');
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     tags={"projects"},
     *     description="Show project",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, ref="#/components/responses/ProjectDetailed"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/projects/{id<\d+>}", methods={"GET"})
     */
    public function getProject(int $id, Request $request, ProjectNormalizer $projectNormalizer, ProjectUserNormalizer $projectUserNormalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Project::class);
        $project = ($this->isGranted("ROLE_MODERATOR"))? $repository->find($id): $repository->findProjectForUser($id, $this->getUser());
        if(!is_null($project)){
            $members = $this->getDoctrine()->getRepository(ProjectUser::class)->findProjectMembers($project);

            return ApiResponse::createSuccessResponse(
                [
                    $projectNormalizer->normalize($project, null, ProjectNormalizer::DETAILED_OUTPUT) +
                    ['members' => $projectUserNormalizer->normalize($members)]
                ]
            );
        }
        throw new NotFoundHttpException('Project not found!');

    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}/name",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Update project name",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateName"),
     *     @OA\Response(
     *         response=200,
     *         description="Project name update completed successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="name", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/projects/{id<\d+>}/name", methods={"PUT"})
     * @Security("is_granted('ROLE_MODERATOR')")
     */
    public function updateProjectName(Request $request, ?Project $project, EventDispatcherInterface $dispatcher)
    {
        if(is_null($project)) throw new NotFoundHttpException('Project not found');
        $form = $this->createForm(UpdateProjectNameType::class, $project);
        if($form->submit($request->request->all())->isValid()) {
            $this->getDoctrine()->getRepository(Project::class)->save($project);
            $dispatcher->dispatch(new ProjectNameUpdateEvent($project));
            return ApiResponse::createSuccessResponse(['name' => $project->getName()]);
        }
        throw new BadRequestHttpException('Bad content!');
    }
    /**
     * @OA\Patch(
     *     path="/api/projects/{id}/members",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Update project members",
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateMembers"),
     *     @OA\Response(
     *         response=200,
     *         description="Project members update completed successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", ref="#/components/schemas/ProjectMembers")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/projects/{id<\d+>}/members", methods={"PATCH"})
     */
    public function updateProjectMembers()
    {
        return new Response();
    }

    /**
     * @OA\Patch(
     *     path="/api/projects/{id}/moderators",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Update project moderators",
     *     @OA\RequestBody(@OA\JsonContent(@OA\Property(property="name"), type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Project moderators update completed successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/projects/{id<\d+>}/moderators", methods={"PATCH"})
     */
    public function updateProjectModerators()
    {
        return new Response();
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}/files",
     *     tags={"topics"},
     *     security={{"bearer":{}}},
     *     description="Show project files",
     *     @OA\Response(
     *         response=200,
     *         description="Finded project files",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/projects/{id}/files", methods={"GET"})
     */
    public function showAttachFiles()
    {
        return new Response();
    }

    /**
     * @OA\Patch(
     *     path="/api/projects/{id}/files",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Update project files",
     *     @OA\RequestBody(@OA\JsonContent(@OA\Property(property="name"), type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Project files list update completed successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="string")
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403")
     * )
     * @Route("/api/projects/{id<\d+>}/files", methods={"PATCH"})
     */
    public function updateProjectFiles()
    {
        return new Response();
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     tags={"projects"},
     *     security={{"bearer":{}}},
     *     description="Remove project",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, description="Success removing project", @OA\JsonContent(@OA\Property(property="empty"))),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/projects/{id<\d+>}", methods={"DELETE"})
     */
    public function removeProject()
    {
        return new Response();
    }


}