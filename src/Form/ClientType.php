<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class ClientType extends AbstractType
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
            
            ->add('tel', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Numéro de téléphone du client'
                ]
            ])
            ->add('agency', EntityType::class,[
                'class'=>"App\Entity\Agency",
                'required'=>true,
                'attr'=>[
                    'class'=>"form-control"
                ],
                'label'=>"Agence d'inscription du client",
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
