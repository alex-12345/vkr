<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecoveryController extends AbstractController
{
    /**
     * @Route("/api/recovery/password", methods={"POST"})
     */
    public function createRecoveryPasswordLink()
    {
        return new Response();
    }
    /**
     * @Route("/api/recovery/password/status", methods={"GET"})
     */
    public function showRecoveryLinkStatus()
    {
        return new Response();
    }

    /**
     * @Route("/api/recovery/password", methods={"PUT"})
     */
    public function changeUserPasswordViaLink()
    {
        //перезаписать в сущность old password и туда же запишем туда дату выдачи токена и дату смены пароля
        //перезаписать пароль
        return new Response();
    }
}