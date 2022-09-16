<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{ private function configForm($label, $class, $placeholder)
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
            ->add('username', TextType::class, $this->configForm("Identifiant", "group-form", "Identifiant"))
            ->add('typeUtilisateur', TextType::class, $this->configForm("Type d'utilisateur", "group-form", "Type Utilisateur"))
            ->add('nom', TextType::class, $this->configForm("Nom", "group-form", "Nom"))
            ->add('prenoms', TextType::class, $this->configForm("Prénoms", "group-form", "Prénoms"))
            ->add('telephone', TextType::class, $this->configForm("Téléphone", "group-form", "+225 "))
            ->add('email', EmailType::class, $this->configForm("E-mail", "group-form", "E-mail"))
            ->add('imageFile', VichImageType::class)
            ->add('Modifier', SubmitType::class, [
                'attr'=>[
                    'class'=> 'btn btn-primary mt-4'
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
