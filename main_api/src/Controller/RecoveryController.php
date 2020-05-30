<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\RecoveryCreatedEvent;
use App\Form\User\ConfirmType;
use App\Form\User\EmailWIthLinkType;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Utils\Encryptor;
use App\Utils\LinkBuilder;
use App\Utils\PayloadHelper;
use App\Utils\TokenManuallyGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;

class RecoveryController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/recovery",
     *     tags={"recovery"},
     *     description="create request on user password change",
     *     @OA\RequestBody(ref="#/components/requestBodies/EmailWIthLinkType"),
     *     @OA\Response(
     *         response=200,
     *         description="Success create request",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="id", type="integer"))
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/recovery", methods={"POST"})
     */
    public function createRecoveryPasswordLink(Request $request, UserRepository $repository, LinkBuilder $linkBuilder, EventDispatcherInterface $dispatcher, PayloadHelper $payloadHelper)
    {
        $form = $this->createForm(EmailWIthLinkType::class, null, ['new_email' => false]);

        if($form->submit($request->request->all())->isValid()) {
            $email = $form->get('email')->getData();
            $user =  $repository->findActiveUserByEmail($email);
            if(!is_null($user)) {
                $link = $form->get('link')->getData();
                $link = $linkBuilder->getRecoveryLink($link, $payloadHelper->getPayloadForRecovery($user));
                $dispatcher->dispatch(new RecoveryCreatedEvent($user, $link));
                return ApiResponse::createSuccessResponse(['id' => $user->getId()]);
            }
            throw new NotFoundHttpException("User not found");
        }
        throw new BadRequestHttpException("Bad content");
    }
    /**
     * @OA\Get(
     *     path="/api/recovery/{id}/status",
     *     tags={"recovery"},
     *     description="check recovey",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Parameter(name="hash", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Access recovery success checked",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", @OA\Property(property="id", type="integer"))
     *          )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/recovery/{id}/status", methods={"GET"})
     * @Entity(name="user", expr="repository.findActiveUser(id)")
     */
    public function showRecoveryLinkStatus(Request $request, ?User $user, PayloadHelper $payloadHelper, Encryptor $encryptor)
    {
        if(is_null($user)) throw new NotFoundHttpException('User with this id was not founded!');
        if($request->query->has('hash')){
            $hash = $request->query->get('hash');
            if($hash === $encryptor->computedCheckSim($payloadHelper->getPayloadForRecovery($user))){
                return ApiResponse::createSuccessResponse(['id' => $user->getId()]);
            }
            throw new AccessDeniedHttpException("Bad hash!");
        }
        throw new BadRequestHttpException('Missing hash parameter!');

    }

    /**
     * @OA\Put(
     *     path="/api/recovery/{id}/status",
     *     tags={"recovery"},
     *     description="check recovey",
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(ref="#/components/requestBodies/confirmRecovery"),
     *     @OA\Response(response=200, ref="#/components/responses/SuccessJWT"),
     *     @OA\Response(response=400, ref="#/components/responses/Error400"),
     *     @OA\Response(response=403, ref="#/components/responses/Error403"),
     *     @OA\Response(response=404, ref="#/components/responses/Error404")
     * )
     * @Route("/api/recovery/{id}/status", methods={"PUT"})
     */
    public function changeUserPasswordViaRecoveryLink(Request $request, ?User $user, PayloadHelper $payloadHelper, Encryptor $encryptor, TokenManuallyGenerator $tokenManuallyGenerator, UserPasswordEncoderInterface $passwordEncoder)
    {
        if(is_null($user)) throw new NotFoundHttpException('User with this id was not founded!');
        $form = $this->createForm(ConfirmType::class, null, ['password' => true]);

        if($form->submit($request->request->all())->isValid()){
            if($form->get('hash')->getData() === $encryptor->computedCheckSim($payloadHelper->getPayloadForRecovery($user))){
                $this->getDoctrine()->getRepository(User::class)->upgradePassword($user, $passwordEncoder->encodePassword($user, $form->get('password')->getData()));
                return $tokenManuallyGenerator->JWTResponseGenerate($user);
            }
            throw new AccessDeniedHttpException("Bad hash!");
        }
        throw new BadRequestHttpException('Missing hash parameter!');
    }
}