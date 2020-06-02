<?php
declare(strict_types=1);

namespace App\Form\ScalarTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @OA\RequestBody(
 *     request="UpdateMembers",
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(property="added_members", type="array", @OA\Items(type="integer")),
 *         @OA\Property(property="deleted_members", type="array", @OA\Items(type="integer"))
 *     )
 * )
 */
class UpdateMembers extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('added_members', CollectionType::class, [
                'entry_type' => IntegerType::class,
                'allow_add' => true
            ])
            ->add('deleted_members', CollectionType::class, [
                'entry_type' => IntegerType::class,
                'allow_add' => true
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}