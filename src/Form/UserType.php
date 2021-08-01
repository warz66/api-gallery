<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['attr' => ['placeholder' => 'Veuillez choisir un nom (facultatif)', 'maxlength' => '30'], 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false])
            ->add('prenom', TextType::class, ['label' => 'Prénom', 'attr' => ['placeholder' => 'Veuillez choisir un prenom (facultatif)', 'maxlength' => '30'], 'label_attr' => ['class' => 'font-weight-bold'], 'required' => false])
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'Veuillez choisir un email valide (obligatoire)'], 'label_attr' => ['class' => 'font-weight-bold']])
            ->add('informations', TextareaType::class, ['label_attr' => ['class' => 'font-weight-bold'], 'required' => false, 'attr' => ['placeholder' => 'Informations complémentaires (facultatif)', 'maxlength' => '255', 'spellcheck' => 'false', 'rows' => '3', 'style' => 'resize:none; overflow:hidden;']])
            ->add('role', EntityType::class, [
                'label' => 'Rôle', 
                'class' => Role::class,
                'empty_data' => null,
                'choice_label' => 'title',
                'label_attr' => ['class' => 'font-weight-bold'],
                'required' => false,
                'placeholder' => 'Aucun droit spécifique',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
