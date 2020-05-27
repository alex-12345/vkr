<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\UserChangesEmailEvent;
use App\Events\UserLockedEvent;
use App\Events\UserUnlockedEvent;
use App\Exception\LockedHttpException;
use App\Form\ScalarTypes\ImageLinkType;
use App\Form\User\ConfirmEmailType;
use App\Form\User\NewEmailType;
use App\Form\ScalarTypes\UserDescriptionType;
use App\Form\User\UpdatePasswordType;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Serializer\Normalizer\UserNormalizer;
use App\Utils\Checker;
use App\Utils\Encryptor;
use App\Utils\LinkBuilder;
use App\Utils\PaginationHelper;
use App\Utils\TokenManuallyGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/api/users/superadmin",
     *     tags={"users"},
     *     description="Show superadmin",
     *     @OA\Parameter(ref="#/components/parameters/workspace_key"),
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     *
     * @Route("/api/users/superadmin", methods={"GET"})
     * @Entity(name="superadmin", expr="repository.findSuperAdmin()")
     */
    public function showAdmin(?User $superadmin, Request $request, UserNormalizer $normalizer, Checker $checker)
    {
        $checker->checkAppSecret($request->get("workspace_key"));

        if(is_null($superadmin)) throw new NotFoundHttpException('User with role "SUPER_ADMIN" not found');

        return ApiResponse::createSuccessResponse($normalizer->normalize($superadmin, null, $normalizer::DETAILED_OUTPUT));
    }

    /**
     * @OA\Put(
     *     path="/api/users/current/password",
     *     tags={"users"},
     *     security={{"bearer":{}}},
     *     description="Update user password",
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdatePassword"),
     *     @OA\Response(
     *         response=200,
     *         description="Success change user password",
     *         @OA\JsonContent(@OA\Property(property="data", @OA\Property(property="password", type="string")))
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=423, ref="#/components/responses/Error423")
     * )
     * @Route("/api/users/current/password", methods={"PUT"})
     */
    public function changeUserPassword(Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $changingUser = $repository->findActiveUser($this->getUser()->getId());
        if(is_null($changingUser)) throw new LockedHttpException('User has been locked!');

        $form = $this->createForm(UpdatePasswordType::class);

        if($form->submit($request->request->all())->isValid() && $passwordEncoder->isPasswordValid($changingUser, $form["old_password"]->getData())){

            $newPassword = $form["new_password"]->getData();
            $repository->upgradePassword($changingUser, $passwordEncoder->encodePassword($changingUser, $newPassword));

            return ApiResponse::createSuccessResponse(
                ['new_password' => $newPassword]
            );
        };

        throw new BadRequestHttpException("Bad data!") ;
    }

    /**
     * @OA\Put(
     *     path="/api/users/current/main_photo",
     *     tags={"users"},
     *     security={{"bearer":{}}},
     *     description="Update user main photo",
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateImageLink"),
     *     @OA\Response(
     *         response=200,
     *         description="Success change user main photo",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="main_photo", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=423, ref="#/components/responses/Error423")
     * )
     * @Route("/api/users/current/main_photo", methods={"PUT"})
     */
    public function changeUserMainPhoto(Request $request, UserRepository $repository)
    {
        $changingUser = $repository->findActiveUser($this->getUser()->getId());

        if(is_null($changingUser)) throw new LockedHttpException('User has been locked!');

        $form = $this->createForm(ImageLinkType::class, $changingUser);

        if($form->submit($request->request->all())->isValid()){

            $repository->save($changingUser);

            return ApiResponse::createSuccessResponse(
                ['main_photo' => $changingUser->getMainPhoto()]
            );
        };

        throw new BadRequestHttpException("Parameter 'main_photo' not valid!") ;

    }
    /**
     * @OA\Put(
     *     path="/api/users/current/description",
     *     tags={"users"},
     *     security={{"bearer":{}}},
     *     description="Update user description",
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateDescription"),
     *     @OA\Response(
     *         response=200,
     *         description="Success update user description",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="description", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=423, ref="#/components/responses/Error423")
     * )
     * @Route("/api/users/current/description", methods={"PUT"})
     */
    public function changeUserDescription(Request $request, UserRepository $repository)
    {
        $changingUser = $repository->findActiveUser($this->getUser()->getId());
        if(is_null($changingUser)) throw new LockedHttpException('User has been locked!');

        $form = $this->createForm(UserDescriptionType::class, $changingUser);

        if($form->submit($request->request->all())->isValid()){

            $repository->save($changingUser);

            return ApiResponse::createSuccessResponse(
                ['description' => $changingUser->getDescription()]
            );
        }
        throw new BadRequestHttpException("Parameter 'description' not valid!") ;
    }


    /**
     * @OA\Post(
     *     path="/api/users/current/email",
     *     tags={"users"},
     *     security={{"bearer":{}}},
     *     description="create request on user email change",
     *     @OA\RequestBody(ref="#/components/requestBodies/NewEmailType"),
     *     @OA\Response(
     *         response=200,
     *         description="Success create request",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="new_email", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409"),
     *     @OA\Response(response=423, ref="#/components/responses/Error423")
     * )
     * @Route("/api/users/current/email", methods={"POST"})
     */
    public function createNewUserEmail(Request $request, UserRepository $repository, LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher)
    {
        $changingUser = $repository->findActiveUser($this->getUser()->getId());
        if(is_null($changingUser)) throw new LockedHttpException('User has been locked!');

        $form = $this->createForm(NewEmailType::class);

        if($form->submit($request->request->all())->isValid()) {
            $newEmail = $form->getData()['new_email'];
            if($changingUser->getEmail() === $newEmail) throw new ConflictHttpException("This user already have this email!");
            $conflictUser = $repository->findUserByEmail($newEmail);
            if(!is_null($conflictUser) && $conflictUser->getIsActive()) throw new ConflictHttpException('User with this email already exist and confirmed');

            $link = $form->getData()['link'];
            $changingUser->setNewEmail($newEmail);

            $repository->save($changingUser);

            $link = $linkBuilder->getInviteConfirmLink($link, ['id' => $changingUser->getId(), 'email'=> $newEmail]);
            $dispatcher->dispatch(new UserChangesEmailEvent($changingUser, $link));

            return ApiResponse::createSuccessResponse(['new_email' => $newEmail]);

        }
        throw new BadRequestHttpException("Bad content!") ;
    }
    /**
     * @OA\Put(
     *     path="/api/users/{id}/email",
     *     tags={"users"},
     *     description="confirm email change",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="hash", type="string"))),
     *     @OA\Response(response=200, ref="#/components/responses/SuccessJWT"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     *     @OA\Response(response=409, ref="#/components/responses/Error409"),
     *     @OA\Response(response=423, ref="#/components/responses/Error423")
     * )
     * @Route("/api/users/{id<\d+>}/email", methods={"PUT"})
     */
    public function changeUserEmail(?User $changingUser, Request $request, Encryptor $encryptor, TokenManuallyGenerator $tokenManuallyGenerator)
    {
        if(is_null($changingUser)) throw new NotFoundHttpException('User not founded!');
        $form = $this->createForm(ConfirmEmailType::class);
        if($form->submit($request->request->all())->isValid()) {
            $hash = $form->getData()['hash'];
            $newEmail = $changingUser->getNewEmail();

            $computedCheckSum = $encryptor->computedCheckSim(['id' => $changingUser->getId(), 'email'=> $newEmail]);
            if($hash === $computedCheckSum)
            {
                $em = $this->getDoctrine()->getManager();
                $conflictUser = $em->getRepository(User::class)->findUserByEmail($newEmail);
                if(!is_null($conflictUser)){
                    if($conflictUser->getIsActive()){
                        throw new ConflictHttpException('User with this email already exist and confirmed');
                    }
                    else{
                        //TODO make transaction here
                        $em->remove($conflictUser);
                        $em->flush();
                    }
                }
                $changingUser->setEmail($newEmail);
                $changingUser->setNewEmail('');

                $em->persist($changingUser);
                $em->flush();

                return $tokenManuallyGenerator->JWTResponseGenerate($changingUser);
            };
            throw new AccessDeniedHttpException("Bad hash");
        }
        throw new BadRequestHttpException('Bad content!');
    }


    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"users"},
     *     description="Show user",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/users/{id<\d+>}", methods={"GET"})
     * @Entity(name="shownUser", expr="repository.findActiveUser(id)")
     */
    public function showUser(?User $shownUser, UserNormalizer $normalizer)
    {
        if(is_null($shownUser)) throw new NotFoundHttpException('User not founded!');
        $option = ($this->getUser()->getId() === $shownUser->getId())? $normalizer::DETAILED_OUTPUT : $normalizer::FULL_OUTPUT;
        return ApiResponse::createSuccessResponse(
            $normalizer->normalize(
                $shownUser, null, $option
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/users/current",
     *     tags={"users"},
     *     description="Show user",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, ref="#/components/responses/UserDetailed"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=423, ref="#/components/responses/Error423")
     * )
     * @Route("/api/users/current", methods={"GET"})
     * @Entity(name="shownUser", expr="repository.findActiveUser(id)")
     */
    public function showCurrentUser(UserRepository $repository, UserNormalizer $normalizer)
    {
        $currentUser = $repository->findActiveUser($this->getUser()->getId());
        if(is_null($currentUser)) throw new LockedHttpException('User has been locked!');
        return ApiResponse::createSuccessResponse(
            $normalizer->normalize(
                $currentUser, null, $normalizer::FULL_OUTPUT
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"users"},
     *     description="Show users",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="page[size]", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="page[number]", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, ref="#/components/responses/UserCollection"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404"),
     * )
     * @Route("/api/users", methods={"GET"})
     */
    public function showUsers(Request $request, UserNormalizer $normalizer, PaginationHelper $paginationHelper)
    {
        $pageParams = $paginationHelper->getPageAndSize($request);
        $paginator = $this->getDoctrine()->getRepository(User::class)->findActiveUsers($pageParams->getNumber(), $pageParams->getSize());
        $usersAmount = count($paginator);
        if($usersAmount){
            return ApiResponse::createSuccessResponse($paginationHelper->paginate($paginator, $normalizer), ['count'=> $usersAmount]);
        }
        throw new NotFoundHttpException('Invite not found!');
    }

    /**
     *
     * @Route("/api/user/{id<\d+>}/roles", methods={"PUT"})
     * Security("is_granted('changeRoles', changingUser)")
     */
    public function changeUserRoles()
    {

        return new Response();
    }
    /**
     * @OA\Post(
     *     path="/api/users/{id}/lock",
     *     tags={"users"},
     *     security={{"bearer":{}}},
     *     description="Locking user",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(
     *         response=200,
     *         description="Success locking user",
     *         @OA\JsonContent(@OA\Property(property="data", @OA\Property(property="is_locked", type="boolean")))
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/users/{id<\d+>}/lock", methods={"POST"})
     * @Entity(name="lockableUser", expr="repository.findActiveUser(id)")
     */
    public function createUserLock(?User $lockableUser, EventDispatcherInterface $dispatcher)
    {
        if(is_null($lockableUser)) throw new NotFoundHttpException('User not Found!');
        $this->denyAccessUnlessGranted('modifyUserLock', $lockableUser);
        $lockableUser->setIsLocked(true);

        $this->getDoctrine()->getRepository(User::class)->save($lockableUser);

        $dispatcher->dispatch(new UserLockedEvent($lockableUser));

        return ApiResponse::createSuccessResponse(
            ['is_locked' => true]
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}/lock",
     *     tags={"users"},
     *     security={{"bearer":{}}},
     *     description="Unlocking user",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(
     *         response=200,
     *         description="Success unlocking user",
     *         @OA\JsonContent(@OA\Property(property="data", @OA\Property(property="is_locked", type="boolean")))
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=401, ref="#/components/responses/Error401JWT"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/users/{id<\d+>}/lock", methods={"DELETE"})
     * @Entity(name="lockableUser", expr="repository.findActiveUser(id)")
     */
    public function removeUserLock(?User $lockableUser, EventDispatcherInterface $dispatcher)
    {
        if(is_null($lockableUser)) throw new NotFoundHttpException('User not Found!');
        $this->denyAccessUnlessGranted('modifyUserLock', $lockableUser);
        $lockableUser->setIsLocked(false);

        $this->getDoctrine()->getRepository(User::class)->save($lockableUser);

        $dispatcher->dispatch(new UserUnlockedEvent($lockableUser));

        return ApiResponse::createSuccessResponse(
            ['is_locked' => false]
        );
    }



}