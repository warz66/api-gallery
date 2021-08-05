<?php

namespace App\Form;

use App\Entity\Image;
use App\Form\TableauType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->add('caption', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Description', 'spellcheck' => 'false', 'maxlength' => '255', 'rows' => '2'],
            ])
            ->add('ordre', NumberType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'config-img-input', 'placeholder' => 'Ordre'],
            ])
            ->add('tableau', TableauType::class, [
                'label' => false,
            ])
            ->add('statut_remove', HiddenType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
