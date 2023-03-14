<?php

namespace App\Form;

use App\Entity\Operations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationTempType extends AbstractType
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
            ->add('base', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Base",
                ]
            ])
            ->add('poideau', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Poids dans l'eau",
                ]
            ])
            ->add('poidair', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Poids dans l'air",
                ]
            ])
            ->add('densite', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Densité",
                    'readonly'=>'readonly',
                ]
            ])
            ->add('karat', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Carat",
                    'readonly'=>'readonly',
                ]
            ])
            ->add('prixu', NumberType::class,[
                'label'=>false,
                'attr'=>[
                   
                    'placeholder'=>"Prix unitaire",
                    'readonly'=>'readonly',
                ]
            ])
            ->add('montant', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Montant",
                    'readonly'=>'readonly',
                ]
            ])
            ->add('avance', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Avance",
                ],
                'required'=>false,
            ])
            // ->add('taux', NumberType::class,[
            //     'label'=>false,
            //     'attr'=>[
            //         'placeholder'=>"Taux dollar",
            //     ],
            //     'required'=>false,
            // ])
            // ->add('qte', NumberType::class,[
            //     'label'=>false,
            //     'attr'=>[
            //         'placeholder'=>"Dollar",
            //         'readonly'=>'readonly',
            //     ],
            //     'required'=>false,
            // ])
            // ->add('avdollar', NumberType::class,[
            //     'label'=>false,
            //     'attr'=>[
            //         'placeholder'=>"Avance dollar",
            //         'readonly'=>'readonly',
            //     ],
            //     'required'=>false,
            // ])
            // ->add('createdAt')
            // ->add('agent')
            // ->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operations::class,
        ]);
    }
}
