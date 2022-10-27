<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{

    private function configForm($label, $class, $placeholder)
    {
        return [
            'label'=>$label,
            'attr'=>[
                'class'=>$class,
                'placeholder'=>$placeholder
            ]
            ];
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, $this->configForm("Username", "form-group", "Username"))
            ->add('typeUtilisateur', TextType::class, $this->configForm("Type Utilisateur", "form-group", "Type Utilisateur"))
            ->add('nom', TextType::class, $this->configForm("Nom", "form", "Nom"))
            ->add('prenoms', TextType::class, $this->configForm("Prénoms", "form-group", "Prénoms"))
            ->add('telephone', TextType::class, $this->configForm("Téléphone", "form-group", "+225 "))
            ->add('email', EmailType::class, $this->configForm("E-mail", "form-group", "E-mail"))
            ->add('userRoles', EntityType::class, [
                'label'=>'Roles',
                'class'=> Role::class,
                'choice_label'=>'title',
                'expanded'=> true,
                'multiple'=>true,
                'attr'=>[
                    'class'=>'form-select',
                    
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
