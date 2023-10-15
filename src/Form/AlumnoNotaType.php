<?php

namespace App\Form;

use App\Entity\AlumnoNota;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlumnoNotaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nota',NumberType::class,[
                'label'=> 'Nota',
                'attr' => [
                    'placeholder' => 'Nota',
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
            ->add('seccion', EntityType::class, array(
                'class' => 'App\Entity\Seccion',
                'choice_label' => 'nombre',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ],
                'empty_data' => 'Seleccione'
            ))
            ->add('alumno', EntityType::class, array(
                'class' => 'App\Entity\Alumno',
                'choice_label' => 'nombres',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ],
                'empty_data' => 'Seleccione'
            ))
            ->add('semestre', EntityType::class, array(
                'class' => 'App\Entity\Semestre',
                'choice_label' => 'nombre',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ],
                'empty_data' => 'Seleccione'
            ))
            ->add('curso', EntityType::class, array(
                'class' => 'App\Entity\Curso',
                'choice_label' => 'nombre',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ],
            ))
            ->add('submit', SubmitType::class, [
                'label'=> 'Guardar',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AlumnoNota::class,
        ]);
    }
}
