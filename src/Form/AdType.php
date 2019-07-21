<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\ImageType;


class AdType extends AbstractType
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
            ->add('title',TextType::class,$this->getconfiguration("Titre de l'annonce","Entrer un titre pour votre annonce"))
            ->add('slug',TextType::class,$this->getconfiguration("Adresse web","Entrer une adresse web valide",[
                "required"=>false
            ]))
             ->add('coverImage',UrlType::class,$this->getconfiguration("Image de couverture ","Entrer l'image de couverture de l'image"))
            ->add('introduction',TextareaType::class,$this->getconfiguration("Introduction ","Donner une introduction globale pour l'annnonce"))
             ->add('content',TextareaType::class,$this->getconfiguration("Contenu de l'annonce ","Donner  une description qui donne vraiment envie de venir chez vous!"))
            ->add('price',MoneyType::class,$this->getconfiguration("Prix ","Entrer le prix du séjour"))
            
           
           
            ->add('rooms',IntegerType::class,$this->getconfiguration("Nombre de Chambres ","Le nombre de chambre disponibles"))
            ->add('images',CollectionType::class,[

                'entry_type'=>ImageType::class,
                'allow_add'=>true,
                'allow_delete'=>true
            ]) ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
