<?php
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserFull",
 *     @OA\Property(property="id", type="integer"),
 *     allOf={@OA\Schema(ref="#/components/schemas/UserBrief")},
 *     @OA\Property(property="roles", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="main_photo", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="is_active", type="boolean"),
 *     @OA\Property(property="registration_date", type="string")
 * )
 * @OA\Response(
 *     response="UserFull",
 *     description="response with user object with all properties except password",
 *     @OA\JsonContent(
 *         @OA\Property(property="data", allOf={@OA\Schema(ref="#/components/schemas/UserFull")})
 *     )
 * )
 * @OA\Response(
 *     response="UserCollection",
 *     description="response with collection user object with all properties except password",
 *     @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(allOf={@OA\Schema(ref="#/components/schemas/UserFull")})),
 *         @OA\Property(property="meta", @OA\Property(property="count", type="integer"))
 *     )
 * )
 *
 */
class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [
            "id" => $object->getId(),
            "first_name" => $object->getFirstName(),
            "second_name" => $object->getSecondName(),
            "roles"=> $object->getRoles(),
            "main_photo" => $object->getMainPhoto(),
            "description" => $object->getDescription()
        ];
        if(in_array('is_active', $context)){
            $data["is_active"] = $object->getIsActive();
        };

        if(in_array('registration_date', $context)){
            $data["registration_date"] = $object->getRegistrationDate()->format(\DateTimeInterface::ISO8601);
        };

        return $data;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof \App\Entity\User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
