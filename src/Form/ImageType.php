<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        $builder
            ->add('caption', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['spellcheck' => 'false', 'maxlength' => '255', 'rows' => '2'],
            ])
            ->add('captionStatus', HiddenType::class, [
                'property_path' => 'caption',
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
