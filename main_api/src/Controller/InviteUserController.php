<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\User;
use App\Events\UserCreatedEvent;
use App\Form\InviteType;
use App\Response\ApiResponse;
use App\Utils\Encryptor;
use App\Utils\LinkBuilder;
use App\Utils\TokenManuallyGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

                $link = $linkBuilder->getInviteConfirmLink($data['link'],['id' =>$newUser->getId()]);
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

                $link = $linkBuilder->getInviteConfirmLink($data['link'], ['id' => $invitedUser->getId()]);
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
     * @Route("/api/invites/{id}", methods={"DELETE"})
     */
    public function removeInvite(int $id,  EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->denyAccessUnlessGranted('remove', 'invites');

        $invitedUser = $entityManager->getRepository(User::class)->findOneBy(["id"=>$id, "isActive" => false]);
        if(!is_null($invitedUser))
        {
            $entityManager->remove($invitedUser);
            $entityManager->flush();
            return ApiResponse::createSuccessResponse(null);
        }
        return ApiResponse::createFailureResponse("Invite not found!", ApiResponse::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/api/invites/{id}/status", methods={"GET"})
     */
    public function showInviteStatus(int $id,  Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, Encryptor $encryptor)
    {
        $encryptPayload = ['id'=> $id];
        if($request->query->has("admin"))
            $encryptPayload['status'] = true;
        $hash = ($request->query->has("hash"))? $request->query->get("hash") : null;

        if($hash === $encryptor->computedCheckSim($encryptPayload)) {
            $rsm = new ResultSetMappingBuilder($entityManager);
            $rsm->addScalarResult('is_active', 'is_active');
            $query = $entityManager->createNativeQuery('SELECT is_active FROM user WHERE id = ? AND is_active = 0 LIMIT 0, 1', $rsm);
            $query->setParameter(1, $id);
            $data = $query->getOneOrNullResult();
            if (count($data)) {
                return ApiResponse::createSuccessResponse($data);
            }
            return ApiResponse::createFailureResponse("Invite not found", ApiResponse::HTTP_NOT_FOUND);
        }
        return ApiResponse::createFailureResponse("Bad hash", ApiResponse::HTTP_FORBIDDEN);
    }

    /**
     * @Route("/api/invites", methods={"GET"})
     */
    public function showInvites(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer)
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

    /**
     * @Route("/api/invites/{id}/status", methods={"PUT"})
     */
    public function confirmInvite(int $id, Request $request, EntityManagerInterface $entityManager, Encryptor $encryptor, UserPasswordEncoderInterface $passwordEncoder, TokenManuallyGenerator $tokenManuallyGenerator)
    {
        $password = ($request->request->has("password"))? $request->request->get("password"): null;
        $encryptPayload = ['id'=> $id];
        if(is_null($password)) {
            $encryptPayload['status'] = true;
        }else if(mb_strlen($password) < 6){
            return ApiResponse::createFailureResponse("Invalid password", ApiResponse::HTTP_BAD_REQUEST);
        };
        $hash = ($request->query->has("hash"))? $request->query->get("hash") : null;
        if($hash === $encryptor->computedCheckSim($encryptPayload)){
            $confirmedUser = $entityManager->getRepository(User::class)->findOneBy(["id"=>$id, "isActive" => false]);
            if(!is_null($confirmedUser)){
                if(($confirmedUser->getRoles() !== User::ROLE_SUPER_ADMIN) && is_null($password)){
                    return ApiResponse::createFailureResponse('Password missing', ApiResponse::HTTP_BAD_REQUEST);
                }
                (!is_null($password))? $confirmedUser->setPassword($passwordEncoder->encodePassword($confirmedUser, $password)):null;

                $confirmedUser->setIsActive(true);
                $entityManager->persist($confirmedUser);
                $entityManager->flush();

                return $tokenManuallyGenerator->JWTResponseGenerate($confirmedUser);
            }

            return ApiResponse::createFailureResponse("Invite not found", ApiResponse::HTTP_NOT_FOUND);
        }
        return ApiResponse::createFailureResponse("Bad hash", ApiResponse::HTTP_FORBIDDEN);

    }


}