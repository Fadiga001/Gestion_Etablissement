<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder'=>'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrez un mot de passe svp ',
                    ]),
                    new Length([
                        'min' => 6,
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            
            ->add('photo', FileType::class, [
                'label' => 'Ajouter une photo (fichier image)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                        'maxSizeMessage'=> "L'image est trop lourde. La taille maximum permise est 1024k"
                    ])
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
