<?php

namespace App\Form;

use App\Entity\AnneeAcademique;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class noteSearchFormType extends AbstractType
{


    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('AnneeScolaire', EntityType::class, [
               'class'=> AnneeAcademique::class,
               'choice_label'=>'AnneeScolaire'
            ])
            ->add('codeClasse', EntityType::class, [
               'class'=> Classe::class,
               'choice_label'=>'codeClasse'
            ])
           

        ;
    }

   
}
