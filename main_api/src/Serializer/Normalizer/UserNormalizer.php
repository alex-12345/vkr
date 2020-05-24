<?php
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
            $data["registration_date"] = $object->getIsActive();
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
