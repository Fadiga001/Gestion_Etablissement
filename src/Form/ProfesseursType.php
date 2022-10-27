<?php

namespace App\Form;

use App\Entity\Professeurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add('imageFile', VichImageType::class, $this->configForm("Photo", "group-form", "Photo"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professeurs::class,
        ]);
    }
}
