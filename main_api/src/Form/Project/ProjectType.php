<?php
declare(strict_types=1);

namespace App\Form\Project;

use Symfony\Component\Form\AbstractType;
use OpenApi\Annotations as OA;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @OA\Schema(
 *     schema="ProjectBrief",
 *     @OA\Property(property="name", type="string", example="New project"),
 *     @OA\Property(property="member_ids", type="array", @OA\Items(type="integer"))
 * )
 * @OA\Schema(
 *     schema="NewProject",
 *     allOf={@OA\Schema(ref="#/components/schemas/ProjectBrief")},
 *     @OA\Property(property="main_topic_name", type="string")
 * )
 * @OA\RequestBody(
 *     request="NewProject",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/NewProject")
 * )
 */
class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 40])
                ]
            ])
            ->add('member_ids', CollectionType::class, [
                'entry_type' => IntegerType::class,
                'allow_add' => true
            ]);
        if($options['main_topic']){
            $builder
                ->add('main_topic_name', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 40])
                    ]
                ]);
        }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'main_topic' => false
        ]);
    }

}