<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de votre projet',
                'attr' => ['class' => 'form-control']
            ])
            ->add('contract', ChoiceType::class, [
                'label' => 'Type de contrat',
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    'Choisissez un type de contrat' => null,
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ])
            ->add('educationLevel', ChoiceType::class, [
                'label' => 'Niveau d\'études souhaité',
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    'Choisissez un niveau d\'études' => null,
                    'BEP / CAP' => 'BEP / CAP',
                    'BAC / BAC PRO' => 'BAC / BAC PRO',
                    'BAC +2' => 'BAC +2',
                    'BAC +3' => 'BAC +3',
                    'BAC +4' => 'BAC +4',
                    'BAC +5' => 'BAC +5',
                    'BAC +8' => 'BAC +8',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ])
            ->add('globalExperienceLevel', ChoiceType::class, [
                'label' => 'Niveau d\'expérience global souhaité',
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    'Choisissez un niveau d\'expérience global' => null,
                    'Débutant' => 'Débutant',
                    '1-2 ans' => '1-2 ans',
                    '2-5 ans' => '2-5 ans',
                    '5-10 ans' => '5-10 ans',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ])
            ->add('experienceLevelAtPosition', ChoiceType::class, [
                'label' => 'Niveau d\'expérience à ce poste souhaité',
                'attr' => ['class' => 'form-select'],
                'choices' => [
                    'Choisissez un niveau d\'expérience global' => null,
                    'Débutant' => 'Débutant',
                    '1-2 ans' => '1-2 ans',
                    '2-5 ans' => '2-5 ans',
                    '5-10 ans' => '5-10 ans',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ])
            ->add('managerJobTitle', TextType::class, [
                'label' => 'Fonction du manager',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])
            ->add('teamSize', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
