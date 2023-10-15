<?php

namespace App\Form;

use App\Entity\Seccion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeccionType extends AbstractType
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
            'data_class' => Seccion::class,
        ]);
    }
}
