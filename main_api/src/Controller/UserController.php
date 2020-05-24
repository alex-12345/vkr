<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\UserChangesEmailEvent;
use App\Events\UserCreatedEvent;
use App\Form\InviteType;
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

class UserController extends AbstractController
{
    /**
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