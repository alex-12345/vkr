<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\UserChangesEmailEvent;
use App\Form\ScalarTypes\ImageUrlType;
use App\Form\User\ConfirmEmailType;
use App\Form\User\NewEmailTypes;
use App\Form\ScalarTypes\PasswordNonEncryptedType;
use App\Form\ScalarTypes\UserDescriptionType;
use App\Response\ApiResponse;
use App\Serializer\Normalizer\UserNormalizer;
use App\Utils\Checker;
use App\Utils\Encryptor;
use App\Utils\LinkBuilder;
use App\Utils\TokenManuallyGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     *     path="/api/users/admin",
     *     tags={"users"},
     *     summary="Show admin",
     *     @OA\Parameter(ref="#/components/parameters/workspace_key"),
     *     @OA\Response(
     *         response=200,
     *         description="Success show admin",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", ref="#/components/schemas/UserWithRegDate")
     *          )
     *     ),
     *     @OA\Response(response=403, description="Denied access", @OA\JsonContent(ref="#/components/schemas/Error403")),
     *     @OA\Response(response=404, description="Admin not found", @OA\JsonContent(ref="#/components/schemas/Error404"))
     * )
     *
     * @Route("/api/users/admin", methods={"GET"})
     * @Entity(name="admin", expr="repository.findSuperAdmin()")
     */
    public function showAdmin(?User $admin, Request $request, UserNormalizer $normalizer, Checker $checker)
    {
        $checker->checkAppSecret($request->get("workspace_key"));

        if(is_null($admin)) throw new NotFoundHttpException('User with role "SUPER_ADMIN" not found');

        return ApiResponse::createSuccessResponse($normalizer->normalize($admin, null, ['is_active', 'registration_date']));
    }

    /**
     * @OA\Put(
     *     path="/api/user/{id}/password",
     *     tags={"users"},
     *     security={"bearer"},
     *     description="Update user password",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdatePassword"),
     *     @OA\Response(
     *         response=200,
     *         description="Success change user password",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="password", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, description="Bad content", @OA\JsonContent(ref="#/components/schemas/Error400")),
     *     @OA\Response(response=403, description="Denied access", @OA\JsonContent(ref="#/components/schemas/Error403")),
     *     @OA\Response(response=404, description="User not found", @OA\JsonContent(ref="#/components/schemas/Error404"))
     * )
     * @Route("/api/user/{id<\d+>}/password", methods={"PUT"})
     * @Security("is_granted('editAccount', changingUser)")
     */
    public function changeUserPassword(Request $request, User $changingUser, UserPasswordEncoderInterface $passwordEncoder)
    {
        //TODO add checking old password and check voter for 404 error
        $form = $this->createForm(PasswordNonEncryptedType::class, $changingUser);

        if($form->submit($request->request->all())->handleRequest(($request))->isValid()){

            $newPassword = $changingUser->getPassword();
            $changingUser->setPassword($passwordEncoder->encodePassword($changingUser, $newPassword));

            $em = $this->getDoctrine()->getManager();
            $em->persist($changingUser);
            $em->flush();

            return ApiResponse::createSuccessResponse(
                ['password' => $newPassword]
            );
        };

        throw new BadRequestHttpException("Parameter 'password' not valid!") ;
    }

    /**
     * @OA\Put(
     *     path="/api/user/{id}/main_photo",
     *     tags={"users"},
     *     security={"bearer"},
     *     description="Update user main photo",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateMainPhoto"),
     *     @OA\Response(
     *         response=200,
     *         description="Success change user main photo",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="main_photo", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, description="Bad content", @OA\JsonContent(ref="#/components/schemas/Error400")),
     *     @OA\Response(response=403, description="Denied access", @OA\JsonContent(ref="#/components/schemas/Error403")),
     *     @OA\Response(response=404, description="User not found", @OA\JsonContent(ref="#/components/schemas/Error404"))
     * )
     * @Route("/api/users/{id<\d+>}/main_photo", methods={"PUT"})
     * @Security("is_granted('editAccount', changingUser)")
     */
    public function changeUserMainPhoto(Request $request, User $changingUser)
    {
        //TODO top
        $form = $this->createForm(ImageUrlType::class, $changingUser);

        if($form->submit($request->request->all())->handleRequest(($request))->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($changingUser);
            $em->flush();

            return ApiResponse::createSuccessResponse(
                ['main_photo' => $changingUser->getMainPhoto()]
            );
        };

        throw new BadRequestHttpException("Parameter 'main_photo' not valid!") ;

    }
    /**
     * @OA\Put(
     *     path="/api/user/{id}/description",
     *     tags={"users"},
     *     security={"bearer"},
     *     description="Update user description",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateDescription"),
     *     @OA\Response(
     *         response=200,
     *         description="Success update user description",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="description", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, description="Bad content", @OA\JsonContent(ref="#/components/schemas/Error400")),
     *     @OA\Response(response=403, description="Denied access", @OA\JsonContent(ref="#/components/schemas/Error403")),
     *     @OA\Response(response=404, description="User not found", @OA\JsonContent(ref="#/components/schemas/Error404"))
     * )
     * @Route("/api/users/{id<\d+>}/description", methods={"PUT"})
     * @Security("is_granted('editAccount', changingUser)")
     */
    public function changeUserDescription(Request $request, User $changingUser)
    {
        $form = $this->createForm(UserDescriptionType::class, $changingUser);

        if($form->submit($request->request->all())->handleRequest(($request))->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($changingUser);
            $em->flush();

            return ApiResponse::createSuccessResponse(
                ['description' => $changingUser->getDescription()]
            );
        }
        throw new BadRequestHttpException("Parameter 'description' not valid!") ;
    }


    /**
     * @OA\Post(
     *     path="/api/user/{id}/email",
     *     tags={"users"},
     *     security={"bearer"},
     *     description="create request on user email change",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateUserEmail"),
     *     @OA\Response(
     *         response=200,
     *         description="Success create request",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="new_email", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, description="Bad content", @OA\JsonContent(ref="#/components/schemas/Error400")),
     *     @OA\Response(response=403, description="Denied access", @OA\JsonContent(ref="#/components/schemas/Error403")),
     *     @OA\Response(response=404, description="User not found", @OA\JsonContent(ref="#/components/schemas/Error404")),
     *     @OA\Response(response=409, description="Conflict with other user", @OA\JsonContent(ref="#/components/schemas/Error404"))
     * )
     * @Route("/api/users/{id<\d+>}/email", methods={"POST"})
     * @Security("is_granted('editAccount', changingUser)")
     */
    public function createNewUserEmail(Request $request, User $changingUser, LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher)
    {
        $form = $this->createForm(NewEmailTypes::class);

        if($form->submit($request->request->all())->handleRequest(($request))->isValid()) {
            $newEmail = $form->getData()['new_email'];
            if($changingUser->getEmail() === $newEmail) throw new ConflictHttpException("This user already have this email!");

            $link = $form->getData()['link'];
            $changingUser->setNewEmail($newEmail);
            $em = $this->getDoctrine()->getManager();

            $conflictUser = $em->getRepository(User::class)->findUserByEmail($newEmail);
            if(!is_null($conflictUser)){
                if($conflictUser->getisActive()){
                    throw new ConflictHttpException('User with this email already exist and confirmed');
                }
                else{
                    $em->remove($conflictUser);
                }
            }
            $em->persist($changingUser);
            $em->flush();

            $link = $linkBuilder->getInviteConfirmLink($link, ['id' => $changingUser->getId(), 'subject'=> 'switchEmail']);
            $dispatcher->dispatch(new UserChangesEmailEvent($changingUser, $link));

            return ApiResponse::createSuccessResponse(['new_email' => $newEmail]);

        }
        throw new BadRequestHttpException("Bad content!") ;
    }
    /**
     * @OA\Put(
     *     path="/api/user/{id}/email",
     *     tags={"users"},
     *     description="create request on user email change",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/confirmUserEmailWithPasswordEnter"),
     *     @OA\Response(
     *         response=200,
     *         description="Success create request",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="new_email", type="string"))
     *          )
     *     ),
     *     @OA\Response(response=400, description="Bad content", @OA\JsonContent(ref="#/components/schemas/Error400")),
     *     @OA\Response(response=403, description="Denied access", @OA\JsonContent(ref="#/components/schemas/Error403")),
     *     @OA\Response(response=404, description="User not found", @OA\JsonContent(ref="#/components/schemas/Error404")),
     *     @OA\Response(response=409, description="Conflict with other user", @OA\JsonContent(ref="#/components/schemas/Error404"))
     * )
     * @Route("/api/users/{id<\d+>}/email", methods={"PUT"})
     */
    public function changeUserEmail(User $changingUser, Request $request, Encryptor $encryptor, TokenManuallyGenerator $tokenManuallyGenerator)
    {
        $form = $this->createForm(ConfirmEmailType::class);
        if($form->submit($request->request->all())->handleRequest(($request))->isValid()) {
            $hash = $form->getData()['hash'];
            $computedCheckSum = $encryptor->computedCheckSim(['id' => $changingUser->getId(), 'subject'=> 'switchEmail']);
            $newEmail = $changingUser->getNewEmail();
            if($hash ===  $computedCheckSum && !is_null($newEmail))
            {
                $em = $this->getDoctrine()->getManager();
                $conflictUser = $em->getRepository(User::class)->findUserByEmail($newEmail);
                if(!is_null($conflictUser)){
                    if($conflictUser->getisActive()){
                        throw new ConflictHttpException('User with this email already exist and confirmed');
                    }
                    else{
                        $em->remove($conflictUser);
                    }
                }
                $changingUser->setEmail($newEmail);
                $changingUser->setNewEmail('');

                $em->persist($changingUser);
                $em->flush();

                return $tokenManuallyGenerator->JWTResponseGenerate($changingUser);
            };
            throw new NotFoundHttpException('User with this parameters not found');
        }
        throw new BadRequestHttpException('Bad content!');
    }


    /**
     * @Route("/api/users/{id<\d+>}", methods={"GET"})
     * @Entity(name="shownUser", expr="repository.findActiveUser(id)")
     */
    public function showUser(User $shownUser, UserNormalizer $normalizer)
    {
        return ApiResponse::createSuccessResponse(
            $normalizer->normalize(
                $shownUser, null, ['is_active', 'registration_date']
            )
        );
    }

    /**
     * @Route("/api/users", methods={"GET"})
     */
    public function showUsers()
    {
        //TODO implement this method\maybe via search function
        return new Response();
    }

    /**
     * @Route("/api/user/{id<\d+>}/roles", methods={"PUT"})
     * Security("is_granted('changeRoles', changingUser)")
     */
    public function changeUserRoles()
    {

        return new Response();
    }
    /**
     * @Route("/api/users/{id<\d+>}/ban", methods={"POST"})
     */
    public function createUserBan()
    {
        //todo need implements this method
        return new Response();
    }

    /**
     * @Route("/api/users/{id<\d+>}/ban", methods={"DELETE"})
     */
    public function removeUserBan()
    {
        //todo need implements this method
        return new Response();
    }



}