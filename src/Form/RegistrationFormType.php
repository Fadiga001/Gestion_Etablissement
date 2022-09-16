<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegistrationFormType extends AbstractType
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
            ->add('username', TextType::class, $this->configForm("Identifiant", "group-form", "Identifiant"))
            ->add('typeUtilisateur', TextType::class, $this->configForm("Type d'utilisateur", "group-form", "Type Utilisateur"))
            ->add('nom', TextType::class, $this->configForm("Nom", "group-form", "Nom"))
            ->add('prenoms', TextType::class, $this->configForm("Prénoms", "group-form", "Prénoms"))
            ->add('telephone', TextType::class, $this->configForm("Téléphone", "group-form", "+225 "))
            ->add('email', EmailType::class, $this->configForm("E-mail", "group-form", "E-mail"))
            ->add('imageFile', VichImageType::class, $this->configForm("Photo", "group-form", "Photo"))
           
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class'=>'group-form',
                    'placeholder'=> 'Mot de passe'
                ],
                'label'=> 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
