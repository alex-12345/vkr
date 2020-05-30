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
 * @OA\Schema(
 *     schema="link",
 *     @OA\Property(property="link", type="link", example="http://client.sapechat.ru/confirmEmail")
 * )
 * @OA\Schema(
 *     schema="NewEmailWithLink",
 *     allOf={@OA\Schema(ref="#/components/schemas/link")},
 *     @OA\Property(property="new_email", type="string", example="example@mail.com")
 * )
 * @OA\Schema(
 *     schema="EmailWithLink",
 *     allOf={@OA\Schema(ref="#/components/schemas/link")},
 *     @OA\Property(property="email", type="string", example="example@mail.com")
 * )
 * @OA\RequestBody(
 *     request="EmailWIthLinkType",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/EmailWithLink")
 * )
 * @OA\RequestBody(
 *     request="NewEmailWIthLinkType",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/NewEmailWithLink")
 * )
 */

class EmailWIthLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emailPropertyName = ($options['new_email'])? 'new_email': 'email';

        $builder
            ->add('link', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Url([
                        'protocols' => ['http', 'https'],
                    ]),
                ]
            ])
            ->add($emailPropertyName, EmailType::class, [
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
            [
                'csrf_protection' => false,
                'new_email' => true
            ]
        );
    }
}