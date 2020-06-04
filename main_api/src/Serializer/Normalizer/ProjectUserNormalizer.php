<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProjectUserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private UserNormalizer $userNormalizer;

    public function __construct(UserNormalizer $userNormalizer)
    {
        $this->userNormalizer = $userNormalizer;
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = [];
        foreach ($object as $member) {
             $data[] = ['created_at'=> $member->getCreatedAt()->format(\DateTimeInterface::ISO8601)]                + $this->userNormalizer->normalize($member->getUser(), null, UserNormalizer::WITHOUT_EMAIL_TINY_OUTPUT);
        }
        return $data;
    }

    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof \App\Entity\ProjectUser;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
