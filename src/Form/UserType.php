<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    private $security;

    // Injection du service Security dans le constructeur (afin de récupérer l'utilisateur courant).
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();

        // Ajout d'un listener sur le formulaire. Déclenchement pre-render. Si l'utilisateur a le ROLE_USER_VALIDATED, alors on affiche le champ email dans le formulaire.
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
            if (in_array('ROLE_USER_VALIDATED', $user->getRoles())) {
                $event->getForm()->add('email', TextType::class);
            }
        });

        $builder
            ->add('picture', FileType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom *',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom *',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('job', TextType::class, [
                'label' => 'Fonction dans l\'entreprise *',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
            if (in_array('ROLE_USER_VALIDATED', $user->getRoles())) {
                $event->getForm()->add('Modifier', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
            } else {
                $event->getForm()->add('Suivant', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
