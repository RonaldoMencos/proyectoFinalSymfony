<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeachAlumnByCarreraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('carrera', EntityType::class, array(
            'placeholder' => 'Seleccione',
            'label' => 'Búsqueda por carrera',
            'required' => false,
            'class' => 'App\Entity\Carrera',
            'choice_label' => 'nombre',
            'attr' => [
                'class' => 'form-control',
                'required' => false
            ],
            'empty_data' => ''
        ))
        ->add('submit', SubmitType::class, [
            'label'=> 'Buscar',
            'attr' => [
                'class' => 'btn btn-primary',
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
