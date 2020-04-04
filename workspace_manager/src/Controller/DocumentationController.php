<?php

declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DocumentationController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function show()
    {
        return $this->redirect('http://doc.sapechat.ru');
    }
}