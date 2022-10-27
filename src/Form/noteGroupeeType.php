<?php

namespace App\Form;

use App\Entity\Notes;
use App\Entity\Matieres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class noteGroupeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('semestre', ChoiceType::class,[
                'choices'=>[
                    'PREMIER SEMESTRE'=> 'PREMIER SEMESTRE',
                    'DEUXIEME SEMESTRE' => 'DEUXIEME SEMESTRE'
                ]
            ])
            ->add('coefficient',IntegerType::class, [
                'attr'=>[
                    'placeholder'=>'Donner un coefficient'
                ]
            ])

            ->add('classe')

            ->add('anneeScolaire')

            ->add('matiere', EntityType::class, [
                'class'=>Matieres::class,
                'choice_label'=>'denomination',

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notes::class,
        ]);
    }
}
