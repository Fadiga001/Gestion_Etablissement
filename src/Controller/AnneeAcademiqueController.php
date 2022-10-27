<?php

namespace App\Controller;

use App\Entity\AnneeAcademique;
use App\Form\AnneeAcademiqueType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnneeAcademiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class AnneeAcademiqueController extends AbstractController
{

    /*
    * Affichage de la liste des années academiques
    */
    #[Route('/annee/academique', name: 'liste_annee')]
    #[IsGranted("ROLE_USER")]
    public function index(AnneeAcademiqueRepository $anneeRepo): Response
    {
        $annee = $anneeRepo->findAll();

        return $this->render('annee_academique/liste_annee.html.twig', [
            'annee' => $annee,
        ]);
    }


    /*
    * Création des Années Academiques
    */

    #[Route('/annee/academique/creer_annees', name: 'creer_annee')]
    public function CreerAnnee( Request $request, EntityManagerInterface $entitymanager): Response
    {

        $annees = new AnneeAcademique();
        $form = $this->createForm(AnneeAcademiqueType::class, $annees);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
                
            
            $annees = $form->getData();
            $entitymanager->persist($annees);
            $entitymanager->flush();
    
            $this->addFlash('success', 'La nouvelle année academique a été créée avec succès !');
    
            return $this->redirectToRoute("liste_annee");
        }
        
       
        return $this->render('annee_academique/creerAnnee.html.twig', [
            'form' => $form->createView(),
            'annee'=>$annees
        ]);

    }


    #[Route('/annee/academique/edit/{anneeScolaire}', name: 'annee_edit')]
    #[ParamConverter('annees_academik', options: ['mapping' => ['anneeScolaire' => 'AnneeScolaire']])]
    
    public function editAnnee(AnneeAcademique $annees_academik, Request $request, EntityManagerInterface $entitymanager): Response
    {

       
        $form = $this->createForm(AnneeAcademiqueType::class, $annees_academik);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {

            $annees_academik = $form->getData();
            $entitymanager->persist($annees_academik);
            $entitymanager->flush();

            $this->addFlash('warning', 'Les données ont été modifiées avec succès !');

            return $this->redirectToRoute("liste_annee");

        }
        

        return $this->render('annee_academique/editAnnee.html.twig', [
            'form' => $form->createView(),
            'anneeAcademik' =>  $annees_academik
        ]);
    }


}
