<?php

namespace App\Form;

use App\Entity\Alumno;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAlumnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('alumno', EntityType::class, array(
            'placeholder' => 'Seleccione',
            'label' => 'Búsqueda alumno',
            'required' => false,
            'class' => 'App\Entity\Alumno',
            'choice_label' => function (Alumno $alumno) {
                return $alumno->getNombres() . ' ' . $alumno->getApellidos();
            },
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
