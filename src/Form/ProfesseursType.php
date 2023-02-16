<?php

namespace App\Form;

use App\Entity\Professeurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfesseursType extends AbstractType
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
            ->add('nom', TextType::class, $this->configForm("Nom et Prénoms", "form-group", "Nom et Prénoms"))
            ->add('matricule', TextType::class, $this->configForm("Matricule", "form-group", "Matricule"))
            ->add('statut', TextType::class, $this->configForm("Statut", "form-group", "Statut"))
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
            'data_class' => Professeurs::class,
        ]);
    }
}
