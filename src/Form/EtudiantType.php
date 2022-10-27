<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\AnneeAcademique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EtudiantType extends AbstractType
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
 
            ->add('matricule', TextType::class, $this->configForm("Matricule", "group-form", "1286/0020/4523/22"))
            ->add('nom', TextType::class, $this->configForm("Nom", "group-form", "Nom"))
            ->add('prenoms', TextType::class, $this->configForm("Prénoms", "group-form", "Prénoms"))
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('lieuNaissance', TextType::class, $this->configForm("Lieu de naissance", "group-form", "Lieu de naissance"))
            ->add('paysNaissance', TextType::class, $this->configForm("Pays de naissance", "group-form", "Pays de naissance"))
            ->add('sexe', ChoiceType::class, [
                'label'=> 'Genre',
                'attr'=> [
                    'class'=> 'form-select'
                ],
                'choices'=> [
                    'Homme' => 'Homme',
                    'Femme'=> 'Femme'
                ], 
            
            ])
            ->add('adresse', TextType::class, $this->configForm("Adresse", "group-form", "Adresse"))
            ->add('telephone', TextType::class, $this->configForm("Téléphone", "group-form", "+225 "))
            ->add('nationalite', TextType::class, $this->configForm("Nationalité", "group-form", "Ivoirienne"))
            ->add('etablissementDeProvenance', TextType::class, $this->configForm("Etablissement de Provenance", "group-form", "Etablissement de Provenance"))
            ->add('personneAContacter', TextType::class, $this->configForm("Personne à contacter(En cas d'urgence)", "group-form", "Personne à contacter"))
            ->add('adresseDePersonneAContacter', TextType::class, $this->configForm("Adresse de personne à contacter(En cas d'urgence)", "group-form", "Adresse de personne à contacter"))
            ->add('telephoneDePersonneAContacter', TextType::class, $this->configForm("Téléphone de personne à contacter(En cas d'urgence)", "group-form", "Téléphone de personne à contacter"))
            ->add('examenPrepare', ChoiceType::class, [
                'label'=> 'Examen Preparé',
                'attr'=> [
                    'class'=> 'form-select'
                ],
                'choices'=> [
                    'BTS' => 'BTS',
                    'DTS'=> 'DTS'
                ], 
                
            ])
            ->add('status', ChoiceType::class, [
                'label'=> 'Status',
                'attr'=> [
                    'class'=> 'form-select'
                ],
                'choices'=> [
                    'Affecté' => 'Affecté(e)',
                    'Non affecté'=> 'Non affecté(e)'
                ], 
                
            ])
          
            ->add('anneeScolaire', EntityType::class, [
                'label'=> 'Année Scolaire',
                'class'=> AnneeAcademique::class,
                'choice_label'=> 'AnneeScolaire',
                'attr'=>[
                    'class'=>'form-select'
                ]
            ])

            ->add('classe', EntityType::class, [
                'label'=> 'Classe',
                'class'=> Classe::class,
                'choice_label'=> 'denomination',
                'attr'=>[
                    'class'=>'form-select'
                ]
            ])
            ->add('filieres', ChoiceType::class, [
                'label'=> 'Filière',
                'attr'=> [
                    'class'=> 'form-select'
                ],
                'choices'=> [
                    'INFORMATIQUE' => 'INFORMATIQUE ET DEVELOPPEUR D\'APPLICATION',
                    'FINANCE COMPTABILITE'=> 'FINANCE COMPTABILITE',
                    'COMMUNICATION VISUELLE'=>'COMMUNICATION VISUELLE',
                    'GESTION COMMERCIALE' => 'GESTION COMMERCIALE',
                    'RESSOURCES HUMAINES ET COMMUNICATION'=> 'RESSOURCES HUMAINES ET COMMUNICATION',
                    'PUBLICITE' => 'PUBLICITE',
                    'LOGISTIQUE'=> 'LOGISTIQUE',
                    
                ], 
                
            ])
            

            ->add('imageFile', VichImageType::class, $this->configForm("Photo", "group-form", "Ajoutez une photo"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
