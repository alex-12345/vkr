<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\{
    HttpFoundation\Response,
    Routing\Annotation\Route
};


class WorkspaceController
{
    /**
     * @Route("/workspace")
     * @return Response
     */
    public function test()
    {
        $number = rand(0,1000);
        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

}