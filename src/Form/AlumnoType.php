<?php

namespace App\Form;

use App\Entity\Alumno;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AlumnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombres', TextType::class,[
                'label'=> 'Nombres',
                'attr' => [
                    'placeholder' => 'Nombres',
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
            ->add('apellidos', TextType::class,[
                'label'=> 'Apellidos',
                'attr' => [
                    'placeholder' => 'Apellidos',
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
            ->add('fechaNacimiento', DateType::class,[
                'label'=> 'Fecha Nacimiento',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
            ->add('foto', FileType::class,[
                'label'=> 'Fotografía',
                'attr' => [
                    'placeholder' => 'Fotografía',
                    'class' => 'form-control'
                ],
                "data_class" => null,
            ])
            ->add('carrera', EntityType::class, array(
                'class' => 'App\Entity\Carrera',
                'choice_label' => 'nombre',
                'attr' => [
                    'class' => 'form-control',
                    'required' => true
                ],
                'empty_data' => 'Seleccione'
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
            'data_class' => Alumno::class,
        ]);
    }
}
