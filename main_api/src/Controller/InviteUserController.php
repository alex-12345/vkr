<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\UserCreatedEvent;
use App\Form\User\ConfirmEmailType;
use App\Form\User\UserType;
use App\Response\ApiResponse;
use App\Serializer\Normalizer\UserNormalizer;
use App\Utils\Checker;
use App\Utils\Encryptor;
use App\Utils\LinkBuilder;
use App\Utils\PaginationHelper;
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
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function createInvite(Request $request, LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher, UserNormalizer $normalizer)
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser, ['roles' => true]);

        if($form->submit($request->request->all())->isValid()){

            $this->denyAccessUnlessGranted('modifyInvite', $newUser);

            $em = $this->getDoctrine()->getManager();
            $conflictEmailUser = $em->getRepository(User::class)->findUserByEmail($newUser->getEmail());

            if(is_null($conflictEmailUser)) {
                $em->persist($newUser);
                $em->flush();

                $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), $linkBuilder->computeEmailConfirmPayload($newUser, false));
                $dispatcher->dispatch(new UserCreatedEvent($newUser,$link));

                return ApiResponse::createSuccessResponse(
                    $normalizer->normalize($newUser, null, $normalizer::DETAILED_OUTPUT)
                );
            }
            throw new ConflictHttpException("Invite or user with that 'email' already exist!");
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
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites/superadmin", methods={"POST"})
     * @Entity(name="superAdmin", expr="repository.findSuperAdmin()")
     */
    public function createAdmin(?User $superAdmin, Request $request, Checker $checker, LinkBuilder $linkBuilder, UserNormalizer $normalizer,  UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $dispatcher)
    {
        if(!is_null($superAdmin)) throw new ConflictHttpException("SuperAdmin already exist!");
        $checker->checkAppSecret($request->get("workspace_key"));

        $superAdmin = new User();
        $form = $this->createForm(UserType::class, $superAdmin, ['password'=>true]);
        if ($form->submit($request->request->all())->isValid()) {

            $conflictEmailUser = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($superAdmin->getEmail());

            if(is_null($conflictEmailUser)){
                $superAdmin->setPassword($passwordEncoder->encodePassword($superAdmin, $form['password']->getData()));
                $superAdmin->setRoles(User::ROLE_SUPER_ADMIN);
                $em = $this->getDoctrine()->getManager();
                $em->persist($superAdmin);
                $em->flush();

                $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), $linkBuilder->computeEmailConfirmPayload($superAdmin, true));
                $dispatcher->dispatch(new UserCreatedEvent($superAdmin, $link));

                return ApiResponse::createSuccessResponse($normalizer->normalize($superAdmin, null, $normalizer::DETAILED_OUTPUT));
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
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409")
     * )
     * @Route("/api/invites/{id<\d+>}", methods={"PUT"})
     * @Entity(name="invitedUser", expr="repository.findInvite(id)")
     */
    public function repeatInvite(?User $invitedUser, Request $request,  LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher, UserNormalizer $normalizer)
    {
        if(is_null($invitedUser)) throw new NotFoundHttpException('This invite was not found!');

        $form = $this->createForm(UserType::class, $invitedUser, ['roles' => true]);
        $form->submit($request->request->all());

        if($form->isValid()) {
            $this->denyAccessUnlessGranted('modifyInvite', $invitedUser);

            $em = $this->getDoctrine()->getManager();
            $conflictUser = $em->getRepository(User::class)->findUserByEmail($invitedUser->getEmail());

            if(is_null($conflictUser) || $conflictUser->getId() === $invitedUser->getId()) {
                $em->persist($invitedUser);
                $em->flush();

                $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), $linkBuilder->computeEmailConfirmPayload($invitedUser, false));
                $dispatcher->dispatch(new UserCreatedEvent($invitedUser, $link));

                return ApiResponse::createSuccessResponse(
                    $normalizer->normalize($invitedUser, null, $normalizer::DETAILED_OUTPUT)
                );
            }
            throw new ConflictHttpException("user or invite with this email already exist");

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
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
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

        $form = $this->createForm(UserType::class, $invitedAdmin, ['password'=>true]);
        $form->submit($request->request->all());

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $conflictUser = $em->getRepository(User::class)->findUserByEmail($invitedAdmin->getEmail());

            if(is_null($conflictUser) || $conflictUser->getId() === $invitedAdmin->getId()) {
                $invitedAdmin->setPassword($passwordEncoder->encodePassword($invitedAdmin, $form->get('password')->getData()));

                $em->persist($invitedAdmin);
                $em->flush();

                $link = $linkBuilder->getInviteConfirmLink($form->get('link')->getData(), $linkBuilder->computeEmailConfirmPayload($invitedAdmin, true));
                $dispatcher->dispatch(new UserCreatedEvent($invitedAdmin, $link));

                return ApiResponse::createSuccessResponse(
                    $normalizer->normalize($invitedAdmin, null, $normalizer::DETAILED_OUTPUT)
                );
            }
            throw new ConflictHttpException("user or invite with this email already exist");
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
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     * )
     * @Route("/api/invites/{id<\d+>}", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @Entity(name="shownInvite", expr="repository.findInvite(id)")
     */
    public function showInvite(?User $shownInvite, UserNormalizer $normalizer)
    {
        if(is_null($shownInvite)) throw new NotFoundHttpException('Invite not found!');

        return ApiResponse::createSuccessResponse(
            $normalizer->normalize($shownInvite, null, $normalizer::DETAILED_OUTPUT)
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
     * @Entity(name="removedInvite", expr="repository.findInvite(id)")
     */
    public function removeInvite(?User $removedInvite, UserNormalizer $normalizer)
    {
        if(is_null($removedInvite)) throw new NotFoundHttpException('Invite not found!');

        $this->denyAccessUnlessGranted('modifyInvite', $removedInvite);

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
     * @Entity(name="invite", expr="repository.findInvite(id)")
     */
    public function showInviteStatus(?User $invite,  Request $request, Encryptor $encryptor)
    {
        if(is_null($invite)) throw new NotFoundHttpException("Invite not found");

        $hash = ($request->query->has("hash"))? $request->query->get("hash") : null;

        if($hash === $encryptor->computedCheckSim($encryptor->computeEmailConfirmPayload($invite, $request->query->has("superadmin")))) {
           return ApiResponse::createSuccessResponse(['isActive' => $invite->getIsActive()]);
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
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function showInvites(Request $request, UserNormalizer $normalizer, PaginationHelper $paginationHelper)
    {
        $pageParams = $paginationHelper->getPageAndSize($request);
        $paginator = $this->getDoctrine()->getRepository(User::class)->findInvites($pageParams->getNumber(), $pageParams->getSize());
        $invitesAmount = count($paginator);
        if($invitesAmount){
            return ApiResponse::createSuccessResponse($paginationHelper->paginate($paginator, $normalizer), ['count'=> $invitesAmount]);
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
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/invites/{id<\d+>}/status", methods={"PUT"})
     * @Entity(name="verifiedInvite", expr="repository.findInvite(id)")
     */
    public function confirmInvite(?User $verifiedInvite, Request $request, EntityManagerInterface $entityManager, Encryptor $encryptor, UserPasswordEncoderInterface $passwordEncoder, TokenManuallyGenerator $tokenManuallyGenerator)
    {
        if(is_null($verifiedInvite)) throw new NotFoundHttpException('Invite not found!');

        $isAdmin = $verifiedInvite->getRoles() === User::ROLE_SUPER_ADMIN;

        $form = $this->createForm(ConfirmEmailType::class, null, ['password'=> !$isAdmin]);

        if($form->submit($request->request->all())->isValid()) {

            $hash = $form->getData()['hash'];
            if($hash === $encryptor->computedCheckSim($encryptor->computeEmailConfirmPayload($verifiedInvite, $isAdmin))) {

                (!$isAdmin) ? $verifiedInvite->setPassword($passwordEncoder->encodePassword($verifiedInvite, $form->get('password')->getData())) : null;

                $verifiedInvite->setIsActive(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($verifiedInvite);
                $em->flush();

                return $tokenManuallyGenerator->JWTResponseGenerate($verifiedInvite);
            }
            throw new AccessDeniedHttpException("Bad hash");
        }
        throw new BadRequestHttpException('Bad content');
    }


}