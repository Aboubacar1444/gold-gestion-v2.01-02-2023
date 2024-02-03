<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname',TextType::class,[
                'label'=>'Nom complet',
                'attr'=>[
                    'placeholder'=>"Prénom & Nom"
                ]
            ])
            ->add('username', TextType::class,[
                'label'=>'Identifiant de connexion',
                'attr'=>[
                    'class'=>'col-md-12',
                    'placeholder'=>"Pseudo de connexion"
                ]
            ])
            ->add('tel', TextType::class,[
                'label'=>'Numéro de téléphone',
                'attr'=>[
                    'placeholder'=>"Numéro de téléphone"
                ]
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
