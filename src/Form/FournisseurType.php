<?php

namespace App\Form;

use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class,[
                'label'=>"Prénom et nom",
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Nom complet du fournisseur",
                ],
                'invalid_message'=>'Veuillez entrer le nom complet du fournisseur.'
            ])
            ->add('entreprise', TextType::class,[

                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Nom de l'entreprise du fournisseur",
                ],
                'required'=>false,
                'invalid_message'=>'Veuillez entrer le nom de l\'entreprise du fournisseur.'

            ] )
            ->add('email', EmailType::class,[
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"E-mail du fournisseur",

                ],
                'required'=>false,
                'invalid_message'=>'Veuillez entrer l\'e-mail du fournisseur.'

            ])
            ->add('telNumber', TelType::class,[
                'label'=>"NºTéléphone",
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Numéro de téléphone du fournisseur",
                ],
                'invalid_message'=>'Veuillez entrer le numero du fournisseur.'

            ])
            ->add('address', TextType::class, [
                'label'=>"Adresse",
                'attr'=>[
                    'class'=>'md-form',
                    'placeholder'=>"Adresse du fournisseur",
                ],
                'invalid_message'=>'Veuillez entrer l\'adresse du fournisseur.'

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}
