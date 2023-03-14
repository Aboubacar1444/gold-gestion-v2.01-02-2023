<?php

namespace App\Form;

use App\Entity\Agency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Nom de l'agence",
                    'class'=>'md-form'
                ],
            ])
            ->add('tel',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Téléphone de l'agence",
                    'class'=>'md-textarea'
                ],
            ])
            ->add('email',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"E-mail de l'agence",
                    'class'=>'md-textarea'
                ],
            ])
            ->add('caisse',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Capital initial de l'agence",
                    'class'=>'md-form'
                ],
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
