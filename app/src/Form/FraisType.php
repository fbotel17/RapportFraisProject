<?php

namespace App\Form;

use App\Entity\Frais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date'
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu'
            ])
            ->add('heureVolantTime', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heures de conduite',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('heuresTotalesTime', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heures totales',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('petit_dejeuner', CheckboxType::class, [
                'label' => 'Petit déjeuner',
                'required' => false,
            ])
            ->add('repas_midi', CheckboxType::class, [
                'label' => 'Repas du midi',
                'required' => false,
            ])
            ->add('repas_soir', CheckboxType::class, [
                'label' => 'Repas du soir',
                'required' => false,
            ])
            ->add('nuit', CheckboxType::class, [
                'label' => 'Nuit',
                'required' => false,
            ])
            ->add('dimanche', CheckboxType::class, [
                'label' => 'Dimanche',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary']
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
