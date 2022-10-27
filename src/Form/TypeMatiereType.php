<?php

namespace App\Form;

use App\Entity\TypeMatieres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeMatiereType extends AbstractType
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
            ->add('codeTypeMatiere', TextType::class,  $this->configForm("Code Type Matière", "form-group", "Code Type Matière"))
            ->add('denomination', TextType::class,  $this->configForm("Dénomination", "form-group", "Dénomination"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeMatieres::class,
        ]);
    }
}
