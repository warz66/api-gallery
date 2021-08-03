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
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Titre'],
            ])
            ->add('technique', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Technique'],
            ])
            ->add('ordre', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Ordre'],
            ])
            ->add('year', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Année'],
            ])
            ->add('width', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Largeur'],
            ])
            ->add('height', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Hauteur'],
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
