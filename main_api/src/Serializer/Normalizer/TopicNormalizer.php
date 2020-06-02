<?php
declare(strict_types=1);
namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TopicBase",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="is_main_project_topic", type="boolean")
 * )
 * */
class TopicNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'is_main_project_topic' => $object->getIsMainProjectTopic()
        ];

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof \App\Entity\Topic;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
