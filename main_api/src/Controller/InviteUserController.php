<?php


namespace App\Controller;


use App\Entity\User;
use App\Events\UserCreatedEvent;
use App\Form\InviteType;
use App\Response\ApiResponse;
use App\Utils\LinkBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class InviteUserController extends AbstractController
{
    /**
     * @Route("/api/invite", methods={"POST"})
     */
    public function createInvite(Request $request, MailerInterface $mailer, LinkBuilder $linkBuilder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, SerializerInterface $serializer)
    {
        $form = $this->createForm(InviteType::class);
        $form->submit($request->request->all())->handleRequest(($request));

        if($form->isValid()){
            $data = $form->getData();
            $newUser = new User($data['first_name'],$data['second_name'], $data['email'], null, $data['roles']);

            $this->denyAccessUnlessGranted('inviteUser', $newUser);

            $conflict_email_user = $entityManager->getRepository(User::class)->findUserByEmail($data['email']);
            if(is_null($conflict_email_user)) {

                $entityManager->persist($newUser);
                $entityManager->flush();

                $link = $linkBuilder->getInviteConfirmLink($data['link'], $data['email']);
                $dispatcher->dispatch(new UserCreatedEvent($newUser,$link));

                return ApiResponse::createSuccessResponse(
                    $serializer->normalize($newUser, null, [
                        AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','username']
                    ]));
            }
            return ApiResponse::createFailureResponse("User with that 'email' already exist!", ApiResponse::HTTP_CONFLICT);
        };

        return ApiResponse::createFailureResponse("Bad content", ApiResponse::HTTP_BAD_REQUEST);
    }
    /**
     * @Route("/api/invite/{id}", methods={"PUT"})
     */
    public function repeatInvite(int $id, Request $request, MailerInterface $mailer, LinkBuilder $linkBuilder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, SerializerInterface $serializer)
    {
        $form = $this->createForm(InviteType::class);
        $form->submit($request->request->all())->handleRequest(($request));

        if($form->isValid()) {
            $invitedUser = $entityManager->getRepository(User::class)->find($id);
            if($invitedUser instanceof User && !$invitedUser->getIsActive() && $invitedUser->getRoles() !== User::ROLE_SUPER_ADMIN){
                $this->denyAccessUnlessGranted('inviteUser', $invitedUser);

                $data = $form->getData();
                //Todo refactor this
                $invitedUser->setFirstName($data['first_name']);
                $invitedUser->setSecondName($data['second_name']);
                $invitedUser->setEmail($data['email']);
                $invitedUser->setRoles($data['roles']);

                $entityManager->persist($invitedUser);
                $entityManager->flush();

                $link = $linkBuilder->getInviteConfirmLink($data['link'], $data['email']);
                $dispatcher->dispatch(new UserCreatedEvent($invitedUser,$link));

                return ApiResponse::createSuccessResponse(
                    $serializer->normalize($invitedUser, null, [
                        AbstractNormalizer::IGNORED_ATTRIBUTES => ['password','salt','username']
                    ]));
            }
            return ApiResponse::createFailureResponse("Invite not found!", ApiResponse::HTTP_NOT_FOUND);
        }
        return ApiResponse::createFailureResponse("Bad content", ApiResponse::HTTP_BAD_REQUEST);
    }

}