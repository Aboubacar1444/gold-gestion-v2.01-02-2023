<?php

namespace App\Form;

use App\Entity\Operations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationDubaiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('tempclient', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Nom du client",
                ]
            ])
            ->add('tel', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Tél du client",
                ]
            ])
            ->add('montant', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Valeur vendue $",
                ]
            ])
            ->add('avdollar', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Comission $",
                ]
            ])
            ->add('numero', NumberType::class,[
                'label'=>false,
                'attr'=>[
                   
                    'placeholder'=>"Lot N° ",
                    
                ]
            ])
            ->add('totalm', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Solde $",
                    'readonly'=>'readonly',
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operations::class,
        ]);
    }
}
