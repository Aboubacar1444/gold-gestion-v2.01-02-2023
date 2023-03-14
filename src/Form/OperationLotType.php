<?php

namespace App\Form;

use App\Entity\Operations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationLotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('poidair', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Poids",
                ],
                'required'=>false,
            ])
            ->add('avdollar', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Avance dollar",
                ]
            ])
            ->add('prixu', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Prix fixe", 'hidden'=>'hidden',
                ],
                'required'=>false,
               
            ])
            
            ->add('avance', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Avance",
                    'readonly'=>'readonly',
                ],
                'required'=>false,
            ])
            ->add('numero', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Lot N°", 
                ]
            ])
            ->add('taux', NumberType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Taux dollar",
                ],
                'required'=>false,
            ])
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
