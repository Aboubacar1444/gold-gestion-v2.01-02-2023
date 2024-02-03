<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class,[

                'type'=> PasswordType::class,
                'first_options'  => array('label' =>'Nouveau Mot de passe', 'attr'=>['placeholder'=>'Mot de passe']),
                'second_options' => array('label' =>'Repeter le mot de passe', 'attr'=>['placeholder'=>'Répéter le mot de passe']),
                'attr'=>[
                    'class'=>'md-form'
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
