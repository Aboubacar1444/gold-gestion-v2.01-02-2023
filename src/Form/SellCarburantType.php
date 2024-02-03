<?php

namespace App\Form;

use App\Entity\SellCarburant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SellCarburantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('litre',NumberType::class,[
                'label'=>"Litre",
                'attr'=>[
                    'class'=>'',
                    'placeholder'=>"Nombre de litre",
                ],
                'invalid_message'=>'Veuillez entrer le nombre de litre.'
            ])
            ->add('typeCarburant', ChoiceType::class,[
                'label'=>"Type de carburant",
                'choices'  => [
                    'Essence' => "Essence",
                    'Gazoil' => "Gazoil",
                ],

                'placeholder'=>"Selectionnez le type de carburant",
                'invalid_message'=>'Veuillez selectionner le type de carburant.'
            ])
            ->add('prix', NumberType::class, [
                'label'=>"Prix total",
                'attr'=>[
                    'class'=>'',
                    'placeholder'=>"Entrez le montant total de la vente",
                ],
                'invalid_message'=>'Veuillez entrer le montant total de la vente.'
            ])

            ->add('clientVehicule', ChoiceType::class,[
                'label'=>"Type de véhicule",
                'choices'  => [
                    'Voiture' => 'Voiture',
                    'Tricycle' => 'Tricycle',
                    'Moto' => "Moto",
                ],
                'placeholder'=>"Selectionnez le type de véhicule",
                'invalid_message'=>'Veuillez selectionner le type de véhicule.'
            ])
//            ->add('client', TextType::class, [
//                'label'=>"Client",
//                'attr'=>[
//                    'class'=>'md-form',
//                    'placeholder'=>"Nom complet du client (facultatif)",
//                ],
//                'required'=>false,
//                'invalid_message'=>'Veuillez entrer le nom complet du client.'
//
//            ])
            ->add('buyAt',null, [
                'label'=>"Date d'achat",
                'attr'=>[
                    'class'=>'',
                    'placeholder'=>"Date de vente de carburant",
                ],
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure(s)', 'minute' => 'Minute(s)', 'second' => 'Seconde(s)',
                ],
//                'required'=> false,

                'date_widget'=>'single_text',
                'invalid_message'=>'Veuillez entrer la date exacte de vente.'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SellCarburant::class,
        ]);
    }
}
