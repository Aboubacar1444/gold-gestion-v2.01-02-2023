<?php

namespace App\Form;

use App\Entity\Operations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationEchangeType extends AbstractType
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
            ->add('montant', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Montant",
                ]
            ])
            ->add('motif', TextareaType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Motif",
                ],
                'required'=>false,
            ])
            ->add('taux', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Taux",
                ]
            ])
            ->add('totalm', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Total",
                    'readonly'=>'readonly',
                ]
            ])
            ->add('avance', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Avance",
                ],
                'required'=>false,
            ])
            ->add('total', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Réliquat",
                    'readonly'=>'readonly',
                ],
                "required"=>false,
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
