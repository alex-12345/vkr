<?php


namespace App\Form;


use App\Entity\User;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class InviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('roles', ChoiceType::class, [
                    'choices' => [User::ROLE_USER,User::ROLE_ADMIN,User::ROLE_MODERATOR],
                    'multiple' =>true
                ]
            )
            ->add('link', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Url([
                            'protocols' => ['http', 'https'],
                        ]),
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}