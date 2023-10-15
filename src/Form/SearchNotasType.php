<?php

namespace App\Form;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchNotasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('semestre', EntityType::class, array(
                'placeholder' => 'Seleccione',
                'label' => 'Búsqueda por semestre',
                'required' => false,
                'class' => 'App\Entity\Semestre',
                'choice_label' => 'nombre',
                'attr' => [
                    'class' => 'form-control',
                    'required' => false
                ],
                'empty_data' => ''
            ))
            ->add('curso', EntityType::class, array(
                'placeholder' => 'Seleccione',
                'label' => 'Búsqueda por curso',
                'required' => false,
                'class' => 'App\Entity\Curso',
                'choice_label' => 'nombre',
                'attr' => [
                    'class' => 'form-control',
                    'required' => false
                ],
                'empty_data' => ''
            ))
            ->add('seccion', EntityType::class, array(
                'placeholder' => 'Seleccione',
                'label' => 'Búsqueda por sección',
                'required' => false,
                'class' => 'App\Entity\Seccion',
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
