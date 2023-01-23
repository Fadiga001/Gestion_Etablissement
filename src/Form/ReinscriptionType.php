<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\AnneeAcademique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ReinscriptionType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 
            
          
            ->add('anneeScolaire', EntityType::class, [
                'label'=> 'Année Scolaire',
                'class'=> AnneeAcademique::class,
                'choice_label'=> 'AnneeScolaire',
                'attr'=>[
                    'class'=>'form-select'
                ], 
                
            ])

            ->add('classe', EntityType::class, [
                'label'=> 'Classe',
                'class'=> Classe::class,
                'choice_label'=> 'denomination',
                'attr'=>[
                    'class'=>'form-select'
                ],
                
            ]);

           
    }

   
}
