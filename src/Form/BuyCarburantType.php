<?php

namespace App\Form;

use App\Entity\BuyCarburant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuyCarburantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('litre', NumberType::class,[
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
                    'placeholder'=>"Entrez le montant total de l'achat",
                ],
                'invalid_message'=>'Veuillez entrer le montant total de l\'achat.'
            ])
            ->add('buyAt', null, [
                'label'=>"Date d'achat",
                'attr'=>[
                    'class'=>'',
                    'placeholder'=>"Date d'achat de carburant",
                ],
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure(s)', 'minute' => 'Minute(s)', 'second' => 'Seconde(s)',
                ],

                'date_widget'=>'single_text',
                'invalid_message'=>'Veuillez entrer la date exacte d\'achat de litre.'
            ])

            ->add('fournisseur', EntityType::class, [
                'class'=>'App\Entity\Fournisseur',
                'label'=>"Fournisseur",
                'attr'=>[
                    'class'=>'',
                ],
                'placeholder'=>"Selectionnez le fournisseur",
                'invalid_message'=>'Veuillez selectionner le fournisseur.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BuyCarburant::class,
        ]);
    }
}
