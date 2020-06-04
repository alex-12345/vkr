<?php
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UsersItem",
 *     @OA\Property(property="id", type="integer"),
 *     allOf={@OA\Schema(ref="#/components/schemas/UserBrief")},
 *     @OA\Property(property="main_photo", type="string"),
 *
 * )
 * @OA\Schema(
 *     schema="UserDetailed",
 *     allOf={@OA\Schema(ref="#/components/schemas/UsersItem")},
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="roles", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="is_locked", type="boolean"),
 *     @OA\Property(property="is_active", type="boolean"),
 *     @OA\Property(property="registration_date", type="string")
 * )
 * @OA\Schema(
 *     schema="UserFull",
 *     allOf={@OA\Schema(ref="#/components/schemas/UserDetailed")},
 *     @OA\Property(property="is_active", type="boolean"),
 *     @OA\Property(property="new_email", type="string")
 * )
 * @OA\Response(
 *     response="UserDetailed",
 *     description="response with user object with main properties",
 *     @OA\JsonContent(
 *         @OA\Property(property="data", allOf={@OA\Schema(ref="#/components/schemas/UserDetailed")})
 *     )
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
 *     description="response with collection user object with main properties",
 *     @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(allOf={@OA\Schema(ref="#/components/schemas/UsersItem")})),
 *         @OA\Property(property="meta", @OA\Property(property="count", type="integer"))
 *     )
 * )
 *
 */
class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    //todo refactor this
    const WITHOUT_EMAIL_TINY_OUTPUT = [];
    const TINY_OUTPUT = ['email'];
    const DETAILED_OUTPUT = ['email', 'description','roles', 'is_active', 'is_locked', 'registration_date'];
    const FULL_OUTPUT = ['email','description','roles', 'is_active', 'is_locked', 'registration_date', 'new_email'];

    public function normalize($object, string $format = null, array $context = self::TINY_OUTPUT): array
    {
        $data = [
            "id" => $object->getId(),
            "first_name" => $object->getFirstName(),
            "second_name" => $object->getSecondName(),
            "main_photo" => $object->getMainPhoto(),
        ];
        if(in_array('email', $context)){
            $data["email"] = $object->getEmail();
        };
        if(in_array('description', $context)){
            $data["description"] = $object->getDescription();
        };
        if(in_array('roles', $context)){
            $data["roles"] = $object->getRoles();
        };
        if(in_array('is_active', $context)){
            $data["is_active"] = $object->getIsActive();
        };
        if(in_array('ban_status', $context)){
            $data["is_locked"] = $object->getIsLocked();
        };
        if(in_array('registration_date', $context)){
            $data["registration_date"] = $object->getRegistrationDate()->format(\DateTimeInterface::ISO8601);
        };
        if(in_array('new_email', $context)){
            $data["new_email"] = $object->getNewEmail();
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
