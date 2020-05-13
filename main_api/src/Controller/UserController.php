<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Utils\SettingHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user/admin", methods={"GET"})
     */
    public function showAdmin(Request $request, SettingHelper $helper, UserRepository $repository, SerializerInterface $serializer)
    {
        $data = json_decode($request->getContent(), true);
        $errorResponse = $helper::checkApiSecret($data, $this->getParameter('app_secret'));
        if($errorResponse){
            return $errorResponse;
        }
        $admin = $repository->findOneAdmin();
        if(is_null($admin)){
            return ApiResponse::createFailureResponse('User with role "ADMIN" not found', ApiResponse::HTTP_NOT_FOUND);
        }
        $response_data = $serializer->normalize($admin, null, [AbstractNormalizer::IGNORED_ATTRIBUTES =>
            ['password','salt','username']]);
        return ApiResponse::createSuccessResponse($response_data);
//todo https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/7-manual-token-creation.md
        // todo https://stackoverflow.com/questions/40519705/symfony-3-jwt-authentication-on-user-registration
    }


}