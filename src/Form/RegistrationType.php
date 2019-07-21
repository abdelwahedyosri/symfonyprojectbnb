<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
class RegistrationType extends AbstractType
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
            ->add('Firstname',TextType::class,$this->getconfiguration('Prénom','Votre prénom..'))
            ->add('Lastname',TextType::class,$this->getconfiguration('Nom','Votre Nom de famille...'))
            ->add('email',EmailType::class,$this->getconfiguration('Email','Votre adresse email'))
            ->add('picture',UrlTYpe::class,$this->getconfiguration('Photo de profil','Votre photo de profil'))
            ->add('hash',PasswordType::class,$this->getconfiguration('Mot de passe','Choisissez votre mot de passe'))
            ->add('passwordConfirm',PasswordType::class,$this->getconfiguration('Confiramtion de Mot de passe','Veuiller confirmer votre mot de passe'))
            ->add('introduction',Texttype::class,$this->getconfiguration('Introduction','présentez vous...'))
            ->add('description',TextareaType::class,$this->getconfiguration('Description détaillée',"c'est le moment de vous présenter"))
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
