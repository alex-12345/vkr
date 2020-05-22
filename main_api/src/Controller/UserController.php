<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\AppSecretCheckEvent;
use App\Events\UserChangesEmailEvent;
use App\Events\UserCreatedEvent;
use App\Form\InviteType;
use App\Form\ScalarTypes\ImageUrlType;
use App\Form\User\ConfirmEmailType;
use App\Form\User\NewEmailTypes;
use App\Form\ScalarTypes\PasswordNonEncryptedType;
use App\Form\ScalarTypes\UserDescriptionType;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Utils\Encryptor;
use App\Utils\LinkBuilder;
use App\Utils\TokenManuallyGenerator;
use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    const SKIPPED_USER_PROPERTY =  ['password','salt','username', 'newEmail'];
    /**
     * @Route("/api/users/admin", methods={"GET"})
     */
    public function showAdmin(Request $request, UserRepository $repository, SerializerInterface $serializer,  EventDispatcherInterface $dispatcher)
    {
        $event = new AppSecretCheckEvent($request->get("workspace_key"));
        $dispatcher->dispatch($event);
        if($event->hasResponse()) return $event->getResponse();

        $admin = $repository->findSuperAdmin();
        if(is_null($admin)){
            throw new NotFoundHttpException('User with role "SUPER_ADMIN" not found');
        }
        $response_data = $serializer->normalize($admin, null, [AbstractNormalizer::IGNORED_ATTRIBUTES =>
            self::SKIPPED_USER_PROPERTY]);
        return ApiResponse::createSuccessResponse($response_data);
    }

    /**
     * @Route("/api/users/admin", methods={"POST"})
     */
    public function createAdmin(Request $request, UserRepository $repository, LinkBuilder $linkBuilder, SerializerInterface $serializer,  UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {

        $event = new AppSecretCheckEvent($request->get("workspace_key"));
        $dispatcher->dispatch($event);
        if($event->hasResponse()) return $event->getResponse();

        $form = $this->createForm(InviteType::class, null, ['password'=>true]);

        $form->submit($request->request->all())->handleRequest(($request));

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $super_admin = $repository->findSuperAdmin();
            $conflict_email_user = $repository->findUserByEmail($data['email']);

            if(is_null($super_admin) && is_null($conflict_email_user)){
                $super_admin = new User($data['first_name'], $data['second_name'], $data['email'], '', USER::ROLE_SUPER_ADMIN);
                $super_admin->setPassword($passwordEncoder->encodePassword($super_admin, $data['password']));

                $entityManager->persist($super_admin);
                $entityManager->flush();

                $link = $linkBuilder->getInviteConfirmLink($data['link'], ['id' => $super_admin->getId(), 'status'=> true]);
                $dispatcher->dispatch(new UserCreatedEvent($super_admin, $link));

                return ApiResponse::createSuccessResponse(
                    $serializer->normalize($super_admin, null, [
                        AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','username']
                    ]));
            }
            throw new ConflictHttpException("SuperAdmin or user with that 'email' already exist!");
        }
        throw new BadRequestHttpException("Bad content!");
    }

    /**
     * @Route("/api/user/{id<\d+>}/password", methods={"PUT"})
     * @Security("is_granted('editAccount', changingUser)")
     */
    public function changeUserPassword(Request $request, User $changingUser, UserPasswordEncoderInterface $passwordEncoder)
    {
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
     * @Route("/api/users/{id<\d+>}/main_photo", methods={"PUT"})
     * @Security("is_granted('editAccount', changingUser)")
     */
    public function changeUserMainPhoto(Request $request, User $changingUser)
    {
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
    public function showUser(User $shownUser, SerializerInterface $serializer)
    {
        return ApiResponse::createSuccessResponse(
            $serializer->normalize(
                $shownUser,
                null,
                [AbstractNormalizer::IGNORED_ATTRIBUTES => self::SKIPPED_USER_PROPERTY]
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