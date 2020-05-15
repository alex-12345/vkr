<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\AppSecretCheckEvent;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
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
     * @Route("/api/user/admin", methods={"GET"})
     */
    public function showAdmin(Request $request, UserRepository $repository, SerializerInterface $serializer,  EventDispatcherInterface $dispatcher)
    {
        $event = new AppSecretCheckEvent($request->get("workspace_key"));
        $dispatcher->dispatch($event);
        if($event->hasResponse()) return $event->getResponse();

        $admin = $repository->findOneAdmin();
        if(is_null($admin)){
            return ApiResponse::createFailureResponse('User with role "ADMIN" not found', ApiResponse::HTTP_NOT_FOUND);
        }
        $response_data = $serializer->normalize($admin, null, [AbstractNormalizer::IGNORED_ATTRIBUTES =>
            ['password','salt','username']]);
        return ApiResponse::createSuccessResponse($response_data);
    }

    /**
     * @Route("/api/user/admin", methods={"POST"})
     */
    public function createAdmin(Request $request, UserRepository $repository, SerializerInterface $serializer,  UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $event = new AppSecretCheckEvent($request->get("workspace_key"));
        $dispatcher->dispatch($event);
        if($event->hasResponse()) return $event->getResponse();

        $form = $this->createForm(UserType::class);

        $form->submit($request->request->all())->handleRequest(($request));

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $admin = $repository->findOneAdmin();
            if(\is_null($admin)){
                $admin = new User();
                $admin->setRoles(['ROLE_ADMIN']);
                $admin->setIsActive(false);
            }
            $admin->setFirstName($data['first_name']);
            $admin->setSecondName($data['second_name']);
            $admin->setEmail($data['email']);
            $admin->setPassword($passwordEncoder->encodePassword($admin, $data['password']));
            $entityManager->persist($admin);
            $entityManager->flush();

            // Call user created event dispatch */

            $response_data = $serializer->normalize($admin, null, [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','username']
            ]);
            return ApiResponse::createSuccessResponse(
                    $serializer->normalize($admin, null, [
                        AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','username']
                    ]));
        }
        return ApiResponse::createFailureResponse("Bad content", ApiResponse::HTTP_BAD_REQUEST);
    }


}