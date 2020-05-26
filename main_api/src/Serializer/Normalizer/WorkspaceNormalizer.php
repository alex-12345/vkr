<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="WorkspaceSetting",
 *     description="Success get installation statuses",
 *     @OA\JsonContent(
 *         @OA\Property(property="data",
 *             @OA\Property(property="mail_server_correct", type="boolean"),
 *             @OA\Property(property="db_correct", type="boolean"),
 *             @OA\Property(property="name", type="string"),
 *         )
 *     )
 * )
 */
class WorkspaceNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function normalize($object, string $format = null, array $context = array()): array
    {
        $data = [
            "mail_server_correct" => $object->getMailServerCorrect(),
            "db_correct" => $object->getDbCorrect(),
            "name" => $object->getName()
        ];

        return $data;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof \App\Entity\Workspace;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
