<?php
declare(strict_types=1);

namespace App\Form\User;

use App\Form\ScalarTypes\PasswordNonEncryptedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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

class ConfirmEmailType extends PasswordNonEncryptedType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        (!$options['password'])? $builder->remove('password'):null;
        $builder
            ->add('hash', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            $resolver->getDefinedOptions() + [
                'password' => false
            ]
        );
    }
}