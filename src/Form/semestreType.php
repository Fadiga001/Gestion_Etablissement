<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class semestreType extends AbstractType
{


    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('semestre', ChoiceType::class, [
                    'label'=> 'semestre',
                    'attr'=> [
                        'class'=> 'form-select'
                    ],
                    'choices'=> [
                        'PREMIER SEMESTRE' => 'PREMIER SEMESTRE',
                        'DEUXIEME SEMESTRE'=> 'DEUXIEME SEMESTRE'
                    ], 
            
                ])
          
                
           

        ;
    }

   
}
