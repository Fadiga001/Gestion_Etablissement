<?php

namespace App\Form;

use App\Entity\Matieres;
use App\Entity\Classe;
use App\Entity\Professeurs;
use App\Entity\TypeMatieres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereType extends AbstractType
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
            ->add('codeMatiere', TextType::class,  $this->configForm("Code Matière", "form-group", "Code Matière"))
            ->add('denomination', TextType::class,  $this->configForm("Dénomination", "form-group", "Dénomination"))
            ->add('TypeMatiere', EntityType::class, [
                'label'=> 'Type de la matière',
                'class'=> TypeMatieres::class,
                'choice_label'=> 'denomination',
                'attr'=> [
                    'class'=> 'form-select',
                    'placeholder'=> 'Type Matière'
                ]
            ])
            ->add('prof', EntityType::class, [
                'label'=> 'Professeur',
                'class'=> Professeurs::class,
                'choice_label'=> 'nom',
                'attr'=> [
                    'class'=> 'form-select',
                    'placeholder'=> 'Professeur'
                ]
            ])
            ->add('classe', EntityType::class, [
                'label'=> 'Classes',
                'class'=> Classe::class,
                'choice_label'=>'codeClasse',
                'expanded'=> true,
                'multiple'=> true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matieres::class,
        ]);
    }
}
