<?php

namespace App\Form;

use App\Entity\AnneeAcademique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ActiverType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('AnneeScolaire', EntityType::class, [
                'class'=> AnneeAcademique::class,
                'choice_label'=>'AnneeScolaire',
                'attr'=> [
                    'class'=>'form-select'
                ],
                
            ])
        ;
    }

   
}
