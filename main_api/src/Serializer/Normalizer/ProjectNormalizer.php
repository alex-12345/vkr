<?php
declare(strict_types=1);
namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ProjectBase",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 * )
 * @OA\Schema(
 *     schema="ProjectMembers",
 *     allOf={@OA\Schema(ref="#/components/schemas/ProjectBase")},
 *     @OA\Property(property="members", type="array", @OA\Items(ref="#/components/schemas/UserBriefId"))
 * )
 * @OA\Schema(
 *     schema="ProjectDetailed",
 *     allOf={@OA\Schema(ref="#/components/schemas/ProjectMembers")},
 *     @OA\Property(property="creator", type="object", ref="#/components/schemas/UserBriefId"),
 *     @OA\Property(property="created_at", type="string")
 * )
 * @OA\Response(
 *     response="ProjectDetailed",
 *     description="response with project object with main properties",
 *     @OA\JsonContent(
 *         @OA\Property(property="data", allOf={@OA\Schema(ref="#/components/schemas/ProjectDetailed")})
 *     )
 * )
 * @OA\Response(
 *     response="ProjectCollection",
 *     description="response with collection project objects with main properties",
 *     @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(allOf={@OA\Schema(ref="#/components/schemas/ProjectBase")})),
 *         @OA\Property(property="meta", @OA\Property(property="count", type="integer"))
 *     )
 * )
 * */
class ProjectNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    const DETAILED_OUTPUT = ['creator', 'created_at'];

    private UserNormalizer $userNormalizer;
    public function __construct(UserNormalizer $userNormalizer)
    {
        $this->userNormalizer = $userNormalizer;
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = [
          'id' => $object->getId(),
          'name' => $object->getName()
        ];

        if(in_array('created_at', $context))
        {
            $data["created_at"] = $object->getCreatedAt()->format(\DateTimeInterface::ISO8601);
        }

        if(in_array('creator', $context))
        {
            //TODO redactor default user normalizer output
            $data["creator"] = $this->userNormalizer->normalize($object->getCreator(), null, UserNormalizer::WITHOUT_EMAIL_TINY_OUTPUT);
        }

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof \App\Entity\Project;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
