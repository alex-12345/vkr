<?php
declare(strict_types=1);

namespace App\Form\ScalarTypes;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="UpdateMainPhoto",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(property="main_photo", type="link", example="http://photo.sapechat.ru/photo.jpg")
 *     )
 * )
 */

class ImageUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('main_photo', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^https?:\/\/\S+(?:jpg|jpeg|png)$/',
                    ])
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}