<?php

namespace App\Form;

use App\Entity\Carrera;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarreraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class,[
                'label'=> 'Nombre',
                'attr' => [
                    'placeholder' => 'Nombre',
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
            ->add('jornada', ChoiceType::class,[
                'label'=> 'Jornada',
                'choices'  => [
                    'Seleccione' => null,
                    'Diario Matutina' => 'Diario Matutina',
                    'Diario Vespertina' => 'Diario Vespertina',
                    'Sabatina' => 'Sabatina',
                    'Dominical' => 'Dominical'
                ],
                'attr' => [
                    'placeholder' => 'Jornada',
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
            ->add('ubicacion', TextType::class,[
                'label'=> 'Ubicación',
                'attr' => [
                    'placeholder' => 'Ubicación',
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                    'required' => true
                ]
            ])
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
            'data_class' => Carrera::class,
        ]);
    }
}
