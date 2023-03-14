<?php

namespace App\Form;

use App\Entity\Agency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Agency1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Nom de l'agence",
                ],
                'label'=>false,
            ])
            ->add('tel', TextareaType::class,[
                'attr'=>[
                    'class'=>'md-textarea',
                    'placeholder'=>"Numéro de téléphone de l'agence",
                ],
                'label'=>false,
            ])
            ->add('email',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"E-mail de l'agence",
                    'class'=>'md-textarea'
                ],
            ])
            ->add('caisse', TextType::class,[
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Capital de départ de l'agence",
                ],
                'label'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agency::class,
        ]);
    }
}
