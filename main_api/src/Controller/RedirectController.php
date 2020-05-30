<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function redirectToDocPage()
    {
        $url = $this->getParameter('app_url');
        return $this->redirect($url.'/swagger/index.html');
    }

}