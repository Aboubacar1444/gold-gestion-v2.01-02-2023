<?php

namespace App\Form;

use App\Entity\Society;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocietyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Nom de votre Société",
                    'class'=>'md-form'
                ],
            ])
            ->add('job',TextareaType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Activité de votre Société",
                    'class'=>'md-textarea'
                ],
            ])
            ->add('description',TextareaType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Description de votre Société",
                    'class'=>'md-textarea'
                ],
            ])
            ->add('email',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"E-mail de votre société",
                    'class'=>'md-textarea'
                ],
            ])
            ->add('tel',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Téléphone de la société",
                    'class'=>'md-textarea'
                ],
            ])
            ->add('caisse',TextType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>"Capital de départ",
                    'class'=>'md-form'
                ]
            ])
            ->add('logo',FileType::class,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Selectionnez le logo de votre entreprise'
                ],
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
