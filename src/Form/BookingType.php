<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends AbstractType
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
            ->add('startdate',DateType::class,$this->getconfiguration("La date d'arrivée","La date à laquelle vous comptez arriver",["widget"=>"single_text"]))
            ->add('enddate',DateType::class,$this->getconfiguration("La date de départ","La date à laquelle vous quittez les lieux",["widget"=>"single_text"]))
            ->add('comment',TextareaType::class,$this->getconfiguration(false,"Si vous avez un commentaire n'hésitez pas à en faire part!"),["required"=>false])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
