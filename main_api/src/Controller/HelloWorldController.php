<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{
    /**
     * @Route("/api/hello", name="hello")
     */
    public function hello()
    {
        $user = $this->getUser();
        return new Response(
            '<html><body>Hello '.var_dump($user).'World</body></html>'
        );
    }
}