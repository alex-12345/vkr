<?php
declare(strict_types=1);

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="NewEmailType",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(property="link", type="link", example="http://client.sapechat.ru/confirmEmail"),
 *         @OA\Property(property="new_email", type="string", example="example@mail.com")
 *     )
 * )
 */

class NewEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('link', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Url([
                        'protocols' => ['http', 'https'],
                    ]),
                ]
            ])
            ->add('new_email', EmailType::class, [
                'required'=>false,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            ['csrf_protection' => false]
        );
    }
}