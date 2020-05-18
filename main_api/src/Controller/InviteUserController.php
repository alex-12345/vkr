<?php
declare(strict_types=1);

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
    const SKIPPED_PROPERTY =  ['password','salt','username'];
    /**
     * @Route("/api/invites", methods={"POST"})
     */
    public function createInvite(Request $request, MailerInterface $mailer, LinkBuilder $linkBuilder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('create', 'invites');

        $form = $this->createForm(InviteType::class);
        $form->submit($request->request->all())->handleRequest(($request));

        if($form->isValid()){

            $data = $form->getData();
            $newUser = new User($data['first_name'],$data['second_name'], $data['email'], null, $data['roles']);


            $conflict_email_user = $entityManager->getRepository(User::class)->findUserByEmail($data['email']);
            if(is_null($conflict_email_user)) {

                $entityManager->persist($newUser);
                $entityManager->flush();

                $link = $linkBuilder->getInviteConfirmLink($data['link'], $data['email']);
                $dispatcher->dispatch(new UserCreatedEvent($newUser,$link));

                return ApiResponse::createSuccessResponse(
                    $serializer->normalize($newUser, null, [
                        AbstractNormalizer::IGNORED_ATTRIBUTES => self::SKIPPED_PROPERTY
                    ]));
            }
            return ApiResponse::createFailureResponse("User with that 'email' already exist!", ApiResponse::HTTP_CONFLICT);
        };

        return ApiResponse::createFailureResponse("Bad content", ApiResponse::HTTP_BAD_REQUEST);
    }
    /**
     * @Route("/api/invites/{id}", methods={"PUT"})
     */
    public function repeatInvite(int $id, Request $request, MailerInterface $mailer, LinkBuilder $linkBuilder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, SerializerInterface $serializer)
    {
        $form = $this->createForm(InviteType::class);
        $form->submit($request->request->all())->handleRequest(($request));

        if($form->isValid()) {
            $this->denyAccessUnlessGranted('create', 'invites');
            $invitedUser = $entityManager->getRepository(User::class)->find($id);

            if($invitedUser instanceof User && !$invitedUser->getIsActive() && $invitedUser->getRoles() !== User::ROLE_SUPER_ADMIN) {
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
                        AbstractNormalizer::IGNORED_ATTRIBUTES => self::SKIPPED_PROPERTY
                    ]));
            }
            return ApiResponse::createFailureResponse("Invite not found!", ApiResponse::HTTP_NOT_FOUND);
        }
        return ApiResponse::createFailureResponse("Bad content", ApiResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/api/invites/{id}", methods={"GET"})
     */
    public function showInvite(int $id,  EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('get', 'invites');

        $invitedUser = $entityManager->getRepository(User::class)->findOneBy(["id"=>$id, "isActive" => false]);
        if($invitedUser instanceof User)
        {
            return ApiResponse::createSuccessResponse(
                $serializer->normalize($invitedUser, null, [
                    AbstractNormalizer::IGNORED_ATTRIBUTES => self::SKIPPED_PROPERTY
                ]));
        }
        return ApiResponse::createFailureResponse("Invite not found!", ApiResponse::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/api/invites", methods={"GET"})
     */
    public function getInvite(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('get', 'invites');

        $param = $request->query->get('page');
        $p_number = (isset($param['number']) && $param['number'] > 0) ? (int) $param['number'] : 1;
        $p_size = (isset($param['size']) && $param['size'] > 0) ? (int) $param['size'] : 10;

        $paginator = $entityManager->getRepository(User::class)->findInvites($p_number, $p_size);
        $invitesAmount = count($paginator);
        if($invitesAmount){
            $invites = [];
            foreach ($paginator as $invite)
            {
                $invites[] = $serializer->normalize($invite, null,[
                    AbstractNormalizer::IGNORED_ATTRIBUTES => self::SKIPPED_PROPERTY
                ]);
            }
            return ApiResponse::createSuccessResponse($invites, ['count'=> $invitesAmount]);
        }
        return ApiResponse::createFailureResponse("Invite not found!", ApiResponse::HTTP_NOT_FOUND);
    }

}