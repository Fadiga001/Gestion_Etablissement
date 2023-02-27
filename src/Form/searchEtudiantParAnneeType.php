<?php

namespace App\Form;


use App\Entity\AnneeAcademique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class searchEtudiantParAnneeType extends AbstractType
{


    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('annee', EntityType::class, [
                'class'=> AnneeAcademique::class,
                'choice_label'=> 'AnneeScolaire',
                'attr'=>[
                    'class'=>'form-select'
                ]
            ])
           

        ;
    }

   
}
