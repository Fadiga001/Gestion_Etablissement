<?php

namespace App\Form;

use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class searchFormType extends AbstractType
{


    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
                ->add('codeClasse', EntityType::class, [
                    'class'=> Classe::class,
                    'choice_label'=> 'codeClasse',
                    'attr'=>[
                        'class'=>'form-select'
                    ]
                ])
           

        ;
    }

   
}
