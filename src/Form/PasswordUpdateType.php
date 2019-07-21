<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends AbstractType
{

     public function getconfiguration($label,$placeholder,$options=[]){

        return array_merge([
                'label'=>$label,
                'attr' =>
                ['placeholder'=>$placeholder]
        ],

        $options);

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Oldpassword',PasswordType::class,$this->getconfiguration("Ancien mot de passe","Donnez votre mot de passe actuel"))
            ->add('newpassword',PasswordType::class,$this->getconfiguration("Nouveau mot de passe","Donnez votre nouveau mot de passe "))
            ->add('confirmpassword',PasswordType::class,$this->getconfiguration("Confirmation du  Nouveau mot de passe","Confirmez votre nouveau mot de passe"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
