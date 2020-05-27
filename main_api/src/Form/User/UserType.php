<?php
declare(strict_types=1);

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="InviteBrief",
 *     allOf={@OA\Schema(ref="#/components/schemas/UserBrief")},
 *     @OA\Property(property="link", type="string")
 * )
 * @OA\Schema(
 *     schema="InviteUser",
 *     allOf={@OA\Schema(ref="#/components/schemas/InviteBrief")},
 *     @OA\Property(property="roles", type="array", @OA\Items(type="string"))
 * )
 * @OA\Schema(
 *     schema="InviteSuperAdmin",
 *     allOf={@OA\Schema(ref="#/components/schemas/InviteBrief")},
 *     @OA\Property(property="password", type="string")
 * )
 * @OA\RequestBody(
 *     request="Invite",
 *     required=true,
 *     description="Invite request body",
 *     @OA\JsonContent(ref="#/components/schemas/InviteUser")
 * )
 * @OA\RequestBody(
 *     request="InviteSuperAdmin",
 *     required=true,
 *     description="Invite superadmin request body",
 *     @OA\JsonContent(ref="#/components/schemas/InviteSuperAdmin")
 *
 * )
 */

class UserType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper($this)
            ->add('first_name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                ]
            ])
            ->add('second_name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 50]),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ]
            ])
            ->add('link', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Url([
                        'protocols' => ['http', 'https'],
                    ]),
                ]
            ]
        );
        if($options['roles']) {
            $builder->add('roles', ChoiceType::class, [
                    'choices' => [User::ROLE_USER,User::ROLE_ADMIN,User::ROLE_MODERATOR],
                    'multiple' =>true
                ]
            );
        }
        if($options['password']) {
            $builder->add('password', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 10]),
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'password' => false,
            'roles' => false
        ]);
    }

    public function mapDataToForms($viewData, iterable $forms)
    {
    }

    public function mapFormsToData($forms, &$viewData)
    {
        $forms = iterator_to_array($forms);

        $viewData->setFirstName($forms['first_name']->getData());
        $viewData->setSecondName($forms['second_name']->getData());
        $viewData->setEmail($forms['email']->getData());
        if(isset($forms['roles'])){
            $viewData->setRoles($forms['roles']->getData());
        }
    }

}