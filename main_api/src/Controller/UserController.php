<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\AppSecretCheckEvent;
use App\Events\UserCreatedEvent;
use App\Form\InviteType;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Utils\LinkBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
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
            return ApiResponse::createFailureResponse('User with role "SUPER_ADMIN" not found', ApiResponse::HTTP_NOT_FOUND);
        }
        $response_data = $serializer->normalize($admin, null, [AbstractNormalizer::IGNORED_ATTRIBUTES =>
            ['password','salt','username']]);
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

            return ApiResponse::createFailureResponse("SuperAdmin or user with that 'email' already exist!", ApiResponse::HTTP_CONFLICT);

        }
        return ApiResponse::createFailureResponse("Bad content", ApiResponse::HTTP_BAD_REQUEST);
    }


}