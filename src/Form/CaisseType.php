<?php

namespace App\Form;

use App\Entity\Society;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaisseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('caisse',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Montant à ajouter à la caisse",
                    'class'=>'md-form',
                    'hidden'=>'hidden',
                ],
                'empty_data'=>0,
                'required'=>false,
            ])
            ->add('dollar',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Montant à ajouter à la caisse",
                    'class'=>'md-form',
                    'hidden'=>'hidden',
                ],
                'empty_data'=>0,
                'required'=>false,
            ])
            ->add('euro',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Montant à ajouter à la caisse",
                    'class'=>'md-form',
                    'hidden'=>'hidden',
                ],
                'empty_data'=>0,
                'required'=>false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Society::class,
        ]);
    }
}
