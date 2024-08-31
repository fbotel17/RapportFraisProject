<?php

namespace App\Form;

use App\Entity\Frais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('lieu')
            ->add('heureVolantTime', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime',
                'label' => 'Heures de conduite',
            ])
            ->add('heuresTotalesTime', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime',
                'label' => 'Heures totales',
            ])
            ->add('petit_dejeuner', CheckboxType::class, [
                'required' => false,
            ])
            ->add('repas_midi', CheckboxType::class, [
                'required' => false,
            ])
            ->add('repas_soir', CheckboxType::class, [
                'required' => false,
            ])
            ->add('nuit', CheckboxType::class, [
                'required' => false,
            ])
            ->add('dimanche', CheckboxType::class, [
                'label' => 'Dimanche',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les modifications',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Frais::class,
        ]);
    }
}
