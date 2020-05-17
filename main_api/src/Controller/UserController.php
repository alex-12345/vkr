<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\AppSecretCheckEvent;
use App\Events\UserCreatedEvent;
use App\Form\UserType;
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
     * @Route("/api/user/admin", methods={"GET"})
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
     * @Route("/api/user/admin", methods={"POST"})
     */
    public function createAdmin(Request $request, UserRepository $repository, LinkBuilder $linkBuilder, SerializerInterface $serializer,  UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {

        $event = new AppSecretCheckEvent($request->get("workspace_key"));
        $dispatcher->dispatch($event);
        if($event->hasResponse()) return $event->getResponse();

        $form = $this->createForm(UserType::class);

        $form->submit($request->request->all())->handleRequest(($request));
        $link = $request->get("confirmation_page");

        if ($form->isSubmitted() && $form->isValid() && filter_var($link, FILTER_VALIDATE_URL)) {
            $data = $form->getData();
            $super_admin = $repository->findSuperAdmin();
            $conflict_email_user = $repository->findUserByEmail($data['email']);

            if(is_null($super_admin) && is_null($conflict_email_user)){
                $super_admin = new User($data['first_name'], $data['second_name'], $data['email'], '', USER::ROLE_SUPER_ADMIN);
                $super_admin->setPassword($passwordEncoder->encodePassword($super_admin, $data['password']));

                $entityManager->persist($super_admin);
                $entityManager->flush();

                $link = $linkBuilder->getInviteConfirmLink($link, $data['email']);
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

    //invite/*
    //COMPLETE пригласить пользователя(по email, имя фамилия, роль) и приласить повторно если уже есть запись с изменением
    //TODO показать список приглашеных с паджинацией
    //TODO удалить приглашение

    //POST invite/confirm
    //TODO принять приглашение(ввод пароля)

    //PUT user/{id}/{photo, email, password, description}
    //TODO редактировать данные пользователя(самим пользователем)
    //  TODO обновить автарку
    //  TODO обновить email(опционально)
    //  TODO обновить пароль
    //  TODO обновить краткую информацию

    //PUT user/{id}/{roles, ban}
    //TODO редактировать данные пользователя(модератором администратором)
    //  TODO изменить права пользователя на модератора\админа и в нижнуюю сторону
    //  TODO заблокировать\разблокировать пользователя(ban=1)

    //POST access/recovery body{email}
    //TODO ввысылка письма на восстановление пароля(по email)(высылка JWT)

    //PUT user/password?token
    //TODO восстановление пароля c его вводом

    //GET user/id
    //TODO получение данных конкретного пользователя
    //GET user?pagin
    //TODO получение списка пользователей с паджинацией

    //GET search/user
    //TODO поиск конкретного пользователя по имени или фамилии (с паджинацией)
    //GET search/invite
    //TODO поиск среди преглашений




}