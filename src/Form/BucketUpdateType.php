<?php

namespace App\Form;

use App\Entity\BucketEntity;
use App\Entity\CategoryEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BucketUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 5,
                ]])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
                'required' => true
            ])

            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'required' => true,
                'choices' => [
                    'Fait' => 'done',
                    'A faire' => 'undone'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => CategoryEntity::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => BucketEntity::class,
            'attr' => [
                'id' => 'create'
            ]
        ]);
    }
}
