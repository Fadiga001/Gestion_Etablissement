<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class searchFormType extends AbstractType
{


    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('search', TextType::class, [
                'attr'=>[
                    'class'=>'group-form ',
                    'placeholder'=> 'Recherche'
                ]
            ])
           

        ;
    }

   
}
