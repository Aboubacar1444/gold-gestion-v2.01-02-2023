<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fullname',TextType::class,[
            'label'=>false,
            'attr'=>[
                'placeholder'=>"Prénom & Nom"
            ]
        ])
        ->add('username', TextType::class,[
            'label'=>false,
            'attr'=>[
                'placeholder'=>"Pseudo de connexion"
            ]
        ])
        ->add('tel', TextType::class,[
            'label'=>false,
            'attr'=>[
                'placeholder'=>"Numéro de téléphone"
            ]
        ])
        ->add('agency', EntityType::class,[
            'class'=>"App\Entity\Agency",
            'required'=>false,
            'attr'=>[
                'class'=>"form-control"
            ],
            'placeholder'=>"Choisissez l'agence",
            'label'=>false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
