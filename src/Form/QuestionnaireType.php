<?php

namespace App\Form;

use App\Entity\Questionnaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du questionnaire'
            ])
            ->add('questions', CollectionType::class, [
                'entry_type' => QuestionsType::class,
                'entry_options' => ['label'=>false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                // By_reference permet de prendre en charge la relation ManyToMany lors de l'enregistrement en base. Il complète la table intermédiaire avec l'id du questionnaire et les ids des questions.
                'by_reference' => false
            ])
            ->add('submit', SubmitType::class, [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questionnaire::class,
        ]);
    }
}
