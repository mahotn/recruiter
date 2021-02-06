<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Intitulé de la question',
                'required' => true,
                'attr' => ['class' => 'form-control add-question-input']
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Choisissez un type' => null,
                    'list' => 'Liste',
                    'date' => 'Date',
                ],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('options')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
