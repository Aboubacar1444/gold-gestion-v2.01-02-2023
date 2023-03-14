<?php

namespace App\Form;

use App\Entity\Operations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tempclient', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Nom complet",
                ]
            ])
            ->add('tel', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Numéro de téléphone",
                ]
            ])
            ->add('product', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Nom du produit",
                ]
            ])
            ->add('prixu', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Prix unitaire",
                ]
            ])
            ->add('qte', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Quantité",
                ]
            ])
            
            ->add('totalm', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Total",
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
