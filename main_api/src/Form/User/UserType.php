<?php


namespace App\Form\User;


use App\Form\User\AbstractUserType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder->add('password', TextType::class, array(
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 10]),
                ]
            ));
    }

}