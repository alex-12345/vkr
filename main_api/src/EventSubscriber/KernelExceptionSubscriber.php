<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Response\ApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="Error400",
 *     description="Bad request",
 *     @OA\JsonContent(
 *         @OA\Property(property="errors",
 *             @OA\Property(property="status", type="integer", example="400"),
 *             @OA\Property(property="title", type="string")
 *         )
 *     )
 *  )
 * @OA\Response(
 *     response="Error403",
 *     description="Access denied",
 *     @OA\JsonContent(
 *         @OA\Property(property="errors",
 *             @OA\Property(property="status", type="integer", example="403"),
 *             @OA\Property(property="title", type="string")
 *         )
 *     )
 *  )
 * @OA\Response(
 *     response="Error404",
 *     description="Not found",
 *     @OA\JsonContent(
 *         @OA\Property(property="errors",
 *             @OA\Property(property="status", type="integer", example="404"),
 *             @OA\Property(property="title", type="string")
 *         )
 *     )
 *  )
 * @OA\Response(
 *     response="Error409",
 *     description="Conflict error",
 *     @OA\JsonContent(
 *         @OA\Property(property="errors",
 *             @OA\Property(property="status", type="integer", example="409"),
 *             @OA\Property(property="title", type="string")
 *         )
 *     )
 *  )
 */
class KernelExceptionSubscriber implements EventSubscriberInterface
{
    public function onExceptionHandle(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if($exception instanceof NotFoundHttpException){
            $event->setResponse(ApiResponse::createFailureResponse($exception->getMessage(), ApiResponse::HTTP_NOT_FOUND));
        }
        if($exception instanceof BadRequestHttpException){
            $event->setResponse(ApiResponse::createFailureResponse($exception->getMessage(), ApiResponse::HTTP_BAD_REQUEST));
        }
        if($exception instanceof AccessDeniedHttpException){
            $event->setResponse(ApiResponse::createFailureResponse($exception->getMessage(), ApiResponse::HTTP_FORBIDDEN));
        }
        if($exception instanceof ConflictHttpException){
            $event->setResponse(ApiResponse::createFailureResponse($exception->getMessage(), ApiResponse::HTTP_CONFLICT));
        }

    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onExceptionHandle'
        ];
    }
}