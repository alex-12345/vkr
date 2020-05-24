<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use App\Form\User\AbstractUserType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class InviteType extends AbstractUserType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder->add('link', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Url([
                        'protocols' => ['http', 'https'],
                    ]),
                ]
            ]
        )->setDataMapper($this);
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
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            $resolver->getDefinedOptions() + [
                'password' => false,
                'roles' => false
            ]
        );
    }

    public function mapDataToForms($viewData, iterable $forms)
    {
        // TODO: Implement mapDataToForms() method.
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