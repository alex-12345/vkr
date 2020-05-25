<?php
declare(strict_types=1);

namespace App\Form\ScalarTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="UpdateDescription",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(property="description", type="string", maximum="255")
 *     )
 * )
 */
class UserDescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class, [
                'constraints' => [
                    new Length(['max' => 255]),
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