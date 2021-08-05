<?php

namespace App\Form;

use App\Entity\Tableau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TableauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => false, 
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Titre', 'maxlength' => '255'],
            ])
            ->add('technique', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Technique', 'maxlength' => '255'],
            ])
            ->add('year', IntegerType::class, [
                'required' => false,
                'label' => false,
                'row_attr' => [
                    'class' => 'w-100'
                ],
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Année', 'min' => '1900', 'max' => '2000'],
            ])
            ->add('width', IntegerType::class, [
                'required' => false,
                'label' => false,
                'row_attr' => [
                    'class' => 'w-100'
                ],
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Largeur', 'min' => '1', 'max' => '1000'],
            ])
            ->add('height', IntegerType::class, [
                'required' => false,
                'label' => false,
                'row_attr' => [
                    'class' => 'w-100'
                ],
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Hauteur', 'min' => '1', 'max' => '1000'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tableau::class,
        ]);
    }
}
