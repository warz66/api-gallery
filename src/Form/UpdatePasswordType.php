<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldPassword', PasswordType::class, ['label' => 'Ancien mot de passe', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Donnez votre mot de passe actuel']])
        ->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les champs "Mot de passe" et "Confirmation du mot de passe" doivent être identiques',
            'required' => true,
            'first_options' => ['label' => 'Nouveau mot de passe', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Au moins 8 caractères minimum']],
            'second_options' => ['label' => 'Confirmation du nouveau mot de passe', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => "Veuillez répéter le mot de passe à l'identique"]]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
