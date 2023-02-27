<?php

namespace App\Form;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title', ChoiceType::class, [
                'label'=> 'Choisir un rôle',
                'attr'=> [
                    'class'=> 'form-select'
                ],
                'choices'=> [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_EDITEUR'=> 'ROLE_EDITEUR',
                    'ROLE_CONSULTER'=> 'ROLE_CONSULTER',
                    'SUPER ADMIN'=> 'SUPER ADMIN'
                ], 
        
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
