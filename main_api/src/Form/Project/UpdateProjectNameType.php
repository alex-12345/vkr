<?php
declare(strict_types=1);

namespace App\Form\Project;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @OA\RequestBody(
 *     request="UpdateName",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(property="name", type="string", maxLength=40)
 *     )
 * )
 */
class UpdateProjectNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40])
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