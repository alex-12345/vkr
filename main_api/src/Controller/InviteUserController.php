<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\UserCreatedEvent;
use App\Form\InviteType;
use App\Form\User\ConfirmEmailType;
use App\Response\ApiResponse;
use App\Serializer\Normalizer\UserNormalizer;
use App\Utils\Checker;
use App\Utils\Encryptor;
use App\Utils\LinkBuilder;
use App\Utils\TokenManuallyGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;

class InviteUserController extends AbstractController
{

    /**
     * @OA\Post(
     *     path="/api/invites",
     *     tags={"invites"},
     *     security={{"bearer":{}}},
     *     description="create user invite to workspace",
     *     @OA\RequestBody(ref="#/components/requestBodies/Invite"),
     *     @OA\Response(response=200, ref="#/components/responses/UserFull"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites", methods={"POST"})
     * @Security("is_granted('create', 'invites')")
     */
    public function createInvite(Request $request, LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher, UserNormalizer $normalizer)
    {
        $newUser = new User();
        $form = $this->createForm(InviteType::class, $newUser, ['roles' => true]);

        if($form->submit($request->request->all())->handleRequest(($request))->isValid()){

            $em = $this->getDoctrine()->getManager();
            $conflict_email_user = $em->getRepository(User::class)->findUserByEmail($newUser->getEmail());

            if(is_null($conflict_email_user)) {
                $em->persist($newUser);
                $em->flush();

                $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), ['id' => $newUser->getId()]);
                $dispatcher->dispatch(new UserCreatedEvent($newUser,$link));

                return ApiResponse::createSuccessResponse(
                    $normalizer->normalize($newUser, null, ['is_active', 'registration_date'])
                );
            }
            throw new ConflictHttpException("User with that 'email' already exist!");
        };
        throw new BadRequestHttpException("Bad content");
    }

    /**
     * @OA\Post(
     *     path="/api/invites/superadmin",
     *     tags={"invites"},
     *     description="create superadmin invite to workspace",
     *     @OA\Parameter(ref="#/components/parameters/workspace_key"),
     *     @OA\RequestBody(ref="#/components/requestBodies/InviteSuperAdmin"),
     *     @OA\Response(response=200, ref="#/components/responses/UserFull"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites/superadmin", methods={"POST"})
     * @Entity(name="admin", expr="repository.findSuperAdmin()")
     */
    public function createAdmin(?User $admin, Request $request, Checker $checker, LinkBuilder $linkBuilder, UserNormalizer $normalizer,  UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $dispatcher)
    {
        if(!is_null($admin)) throw new ConflictHttpException("SuperAdmin already exist!");

        $checker->checkAppSecret($request->get("workspace_key"));

        $superAdmin = new User();
        $form = $this->createForm(InviteType::class, $superAdmin, ['password'=>true]);

        if ($form->submit($request->request->all())->handleRequest(($request))->isValid()) {

            $conflict_email_user = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($superAdmin->getEmail());

            if(is_null($conflict_email_user)){

                $admin->setPassword($passwordEncoder->encodePassword($admin, $form->get('password')->getData()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($admin);
                $em->flush();

                $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), ['id' => $admin->getId(), 'status'=> true]);
                $dispatcher->dispatch(new UserCreatedEvent($admin, $link));

                return ApiResponse::createSuccessResponse($normalizer->normalize($admin, null, ['is_active', 'registration_date']));
            }
            throw new ConflictHttpException("User with that 'email' already exist!");
        }
        throw new BadRequestHttpException("Bad content!");
    }

    /**
     * @OA\Put(
     *     path="/api/invites/{id}",
     *     tags={"invites"},
     *     description="repeat or update invite",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/Invite"),
     *     @OA\Response(response=200, ref="#/components/responses/UserFull"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites/{id<\d+>}", methods={"PUT"})
     * @Security("is_granted('create', 'invites')")
     * @Entity(name="invitedUser", expr="repository.findInvite(id)")
     */
    public function repeatInvite(?User $invitedUser, Request $request,  LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher, UserNormalizer $normalizer)
    {
        if(is_null($invitedUser)) throw new NotFoundHttpException('This invite was not found!');

        $form = $this->createForm(InviteType::class, $invitedUser, ['roles' => true]);
        $form->submit($request->request->all())->handleRequest(($request));

        if($form->isValid()) {
            //TODO check conflict email if email update
            $em = $this->getDoctrine()->getManager();
            $em->persist($invitedUser);
            $em->flush();

            $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), ['id' => $invitedUser->getId()]);
            $dispatcher->dispatch(new UserCreatedEvent($invitedUser, $link));
            return ApiResponse::createSuccessResponse(
                $normalizer->normalize($invitedUser, null, ['is_active', 'registration_date'])
            );

        }
        throw new BadRequestHttpException('Bad content!');
    }

    /**
     * @OA\Put(
     *     path="/api/invites/superadmin",
     *     tags={"invites"},
     *     description="repeat or update superadmin",
     *     @OA\Parameter(ref="#/components/parameters/workspace_key"),
     *     @OA\RequestBody(ref="#/components/requestBodies/InviteSuperAdmin"),
     *     @OA\Response(response=200, ref="#/components/responses/UserFull"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites/superadmin", methods={"PUT"})
     * @Entity(name="invitedAdmin", expr="repository.findInviteAdmin()")
     */
    public function repeatSuperAdminInvite(?User $invitedAdmin, Request $request, Checker $checker, LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher, UserNormalizer $normalizer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $checker->checkAppSecret($request->get("workspace_key"));
        if(is_null($invitedAdmin)) throw new NotFoundHttpException('Invite not found!');

        $form = $this->createForm(InviteType::class, $invitedAdmin, ['password'=>true]);
        $form->submit($request->request->all())->handleRequest(($request));

        if($form->isValid()) {
            $invitedAdmin->setPassword($passwordEncoder->encodePassword($invitedAdmin, $form->get('password')->getData()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($invitedAdmin);
            $em->flush();

            $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), ['id' => $invitedAdmin->getId()]);
            $dispatcher->dispatch(new UserCreatedEvent($invitedAdmin, $link));
            return ApiResponse::createSuccessResponse(
                $normalizer->normalize($invitedAdmin, null, ['is_active', 'registration_date'])
            );

        }
        throw new BadRequestHttpException('Bad content!');
    }

    /**
     * @OA\Get(
     *     path="/api/invites/{id}",
     *     tags={"invites"},
     *     description="Show invite",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, ref="#/components/responses/UserFull"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     * )
     * @Route("/api/invites/{id<\d+>}", methods={"GET"})
     * @Security("is_granted('get', 'invites')")
     * @Entity(name="shownInvite", expr="repository.findInvite(id)")
     */
    public function showInvite(?User $shownInvite, UserNormalizer $normalizer)
    {
        if(is_null($shownInvite)) throw new NotFoundHttpException('Invite not found!');

        return ApiResponse::createSuccessResponse(
            $normalizer->normalize($shownInvite, null, ['is_active', 'registration_date'])
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/invites/{id}",
     *     tags={"invites"},
     *     description="Remove invite",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, description="remove invite", @OA\JsonContent(@OA\Property(property="empty"))),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     * )
     * @Route("/api/invites/{id<\d+>}", methods={"DELETE"})
     * @Security("is_granted('create', 'invites')")
     * @Entity(name="removedInvite", expr="repository.findInvite(id)")
     */
    public function removeInvite(?User $removedInvite, UserNormalizer $normalizer)
    {
        if(is_null($removedInvite)) throw new NotFoundHttpException('Invite not found!');

        $em = $this->getDoctrine()->getManager();
        $em->remove($removedInvite);
        $em->flush();

        return ApiResponse::createSuccessResponse(null);

    }

    /**
     * @OA\Get(
     *     path="/api/invites/{id}/status",
     *     tags={"invites"},
     *     description="Show invite status",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Parameter(name="hash", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="superadmin", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="invite status",
     *          @OA\JsonContent(@OA\Property(property="data", @OA\Property(property="is_active", type="boolean")))
     *     ),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     * )
     * @Route("/api/invites/{id<\d+>}/status", methods={"GET"})
     */
    public function showInviteStatus(int $id,  Request $request, Encryptor $encryptor)
    {
        $hash = ($request->query->has("hash"))? $request->query->get("hash") : null;
        $encryptPayload = ['id'=> $id] + (($request->query->has("superadmin"))? ['status' => true]:[]);

        if($hash === $encryptor->computedCheckSim($encryptPayload)) {
            //TODO maybe refactor repo.findInviteStatus
            $data = $this->getDoctrine()->getRepository(User::class)->findInviteStatus($id);
            if (!is_null($data)) {
                return ApiResponse::createSuccessResponse($data);
            }
            throw new NotFoundHttpException("Invite not found");
        }
        throw new AccessDeniedHttpException("Bad hash");
    }

    /**
     * @OA\Get(
     *     path="/api/invites",
     *     tags={"invites"},
     *     description="Show invite",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="page[size]", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="page[number]", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, ref="#/components/responses/UserCollection"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     * )
     * @Route("/api/invites", methods={"GET"})
     * @Security("is_granted('get', 'invites')")
     */
    public function showInvites(Request $request, UserNormalizer $normalizer)
    {
        $param = $request->query->get('page');
        $p_number = (isset($param['number']) && $param['number'] > 0) ? (int) $param['number'] : 1;
        $p_size = (isset($param['size']) && $param['size'] > 0) ? (int) $param['size'] : 10;

        $paginator = $this->getDoctrine()->getRepository(User::class)->findInvites($p_number, $p_size);
        $invitesAmount = count($paginator);
        if($invitesAmount){
            $invites = [];
            foreach ($paginator as $invite)
            {
                $invites[] = $normalizer->normalize($invite, null, ['is_active', 'registration_date']);
            }
            return ApiResponse::createSuccessResponse($invites, ['count'=> $invitesAmount]);
        }
        throw new NotFoundHttpException('Invite not found!');
    }

    /**
     * @OA\Put(
     *     path="/api/invites/{id}/status",
     *     tags={"invites"},
     *     description="confirm invite",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/confirmInvite"),
     *     @OA\Response(response=200, ref="#/components/responses/SuccessJWT"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites/{id<\d+>}/status", methods={"PUT"})
     * @Entity(name="verifiedInvite", expr="repository.findInvite(id)")
     */
    public function confirmInvite(?User $verifiedUser, Request $request, EntityManagerInterface $entityManager, Encryptor $encryptor, UserPasswordEncoderInterface $passwordEncoder, TokenManuallyGenerator $tokenManuallyGenerator)
    {
        if(is_null($verifiedUser)) throw new NotFoundHttpException('Invite not found!');

        $isAdmin = $verifiedUser->getRoles() === User::ROLE_SUPER_ADMIN;

        $form = $this->createForm(ConfirmEmailType::class, null, ['password'=> !$isAdmin]);
        if($form->submit($request->request->all())->handleRequest(($request))->isValid()) {
            $hash = $form->getData()['hash'];
            if($hash !== $encryptor->computedCheckSim(['id'=> $verifiedUser->getId()] + ($isAdmin)? []: ['status'=>true]))
                throw new AccessDeniedHttpException("Bad hash");

            //TODO maybe check conflict
            ($isAdmin)?$verifiedUser->setPassword($passwordEncoder->encodePassword($verifiedUser, $form->getData()['password'])):null;
            $verifiedUser->setIsActive(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($verifiedUser);
            $em->flush();

            return $tokenManuallyGenerator->JWTResponseGenerate($verifiedUser);
        }
        throw new BadRequestHttpException('Bad content');
    }


}