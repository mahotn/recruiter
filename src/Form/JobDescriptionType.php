<?php

namespace App\Form;

use App\Entity\JobDescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobDescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jobTitle', TextType::class, [
                'label' => 'Titre du poste',
                'attr' => ['class'=> 'form-control'],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du métier',
                'attr' => ['class' => 'form-control', 'rows'=> '10'],
                'required' => true,
            ])
            ->add('missions', CollectionType::class, [
                'label' => false,
                'entry_type' => MissionType::class,
                'entry_options' => ['label'=>false],
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('skills', CollectionType::class, [
                'label' => false,
                'entry_type' => SkillType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobDescription::class,
        ]);
    }
}
