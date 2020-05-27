<?php
declare(strict_types=1);

namespace App\Form\User;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="UpdatePassword",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(property="old_password", type="string", minLength=1),
 *         @OA\Property(property="new_password", type="string", minLength=6, maxLength=255)
 *     )
 * )
 */

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('new_password', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 255])
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