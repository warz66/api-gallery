<?php

namespace App\Form;

use App\Entity\Galerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GalerieType extends AbstractType
{   
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'attr' => ['placeholder' => 'Veuillez choisir un titre', 'maxlength' => '100'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('reference', TextType::class, ['label' => 'Référence', 'required' => false, 'attr' => ['placeholder' => 'Veuillez choisir un nom de référence pour cette galerie', 'maxlength' => '100'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('theme', TextType::class, ['label' => 'Thème', 'required' => false, 'attr' => ['placeholder' => 'Veuillez choisir un thème (genre artistique) pour cette galerie', 'maxlength' => '100'],'label_attr' => ['class' => 'font-weight-bold']])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false, 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Veuillez décrire la galerie', 'spellcheck' => 'false', 'maxlength' => '255', 'rows' => '3', 'style' => 'resize:none; overflow:hidden;']])
            ->add('imageFile', VichImageType::class, ['storage_resolve_method' => VichImageType::STORAGE_RESOLVE_PATH_RELATIVE, 'label' => 'Image de couverture', 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'allow_delete' => false, 'download_label' => false,'download_uri' => false,'imagine_pattern' => 'galerie_cover_thumb', 'attr' => ['accept' => '.jpg, .png']])
            ->add('par_ordre', ChoiceType::class, ['label' => 'Trier par', 'label_attr' => ['class' => 'font-weight-bold'], 'choices' => ['Ordre des tableaux croissant' => 'OrdreTableauAsc', 'Ordre des tableaux décroissant' => 'OrdreTableauDesc'], 'placeholder' => false])
            ->add('statut', CheckboxType::class, ['required' => false])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'data' => $options['pagination'],
                'mapped' => false, // info : https://symfony.com/doc/current/reference/forms/types/collection.html#mapped
                'allow_add' => false,
                'allow_delete' => false, // info : https://symfony.com/doc/current/reference/forms/types/collection.html#allow-delete
                'attr' => ['class' => 'grid are-images-unloaded'],
                'label' => false,
                //'allow_extra_fields' => true,
            ])
            ->add('up_to_page', HiddenType::class, [
                'mapped' => false,
                'attr' => ['value' => '1']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Galerie::class,
            'pagination' => null,
        ]);
    }
}
