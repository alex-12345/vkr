<?php
declare(strict_types=1);

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ConfirmEmail",
 *     @OA\Property(property="hash", type="string"),
 *     @OA\Property(property="password", type="string")
 * )
 * @OA\RequestBody(
 *     request="confirmInvite",
 *     required=true,
 *     description="Data for confirm user or superadmin email. If the user has the super admin role, remove the password property.",
 *     @OA\JsonContent(ref="#/components/schemas/ConfirmEmail")
 * )
 */

class ConfirmEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hash', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ]);
        if($options['password']){
            $builder->add('password', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 255])
                ]
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'password' => false
        ]);
    }
}