<?php

namespace App\Controller;

use App\Form\ActiverType;
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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AnneeAcademiqueController extends AbstractController
{

    /*
    * Affichage de la liste des années academiques
    */
    #[Route('/annee/academique', name: 'liste_annee')]
    public function index(AnneeAcademiqueRepository $anneeRepo, Request $request, SessionInterface $session): Response
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

        $annee = new AnneeAcademique();

        $form = $this->createForm(AnneeAcademiqueType::class, $annee);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            
        }
        
       
        return $this->render('annee_academique/creerAnnee.html.twig', [
            'form' => $form->createView(),
            'annee'=>$annee
        ]);

    }


    /*
    * Affichage de la liste des années academiques
    */
    #[Route('/annee/academique/{id}', name: 'activer')]
    #[IsGranted("ROLE_USER")]
    public function Activer($id, AnneeAcademique $annee, EntityManagerInterface $manager, AnneeAcademiqueRepository $anneeRepo): Response
    {
       
        $annees = $anneeRepo->findAll();
        
        for($i=0; $i<sizeof($annees); $i++)
        {
            if($annees[$i]->getId() == $id)
            {
                $annees[$i]->setActive(($annees[$i]->isActive() ? false : true));
                $manager->persist($annees[$i]);
                $manager->flush();
            }else{
                $annees[$i]->setActive(($annees[$i]->isActive() ? false : 0));
                $manager->persist($annees[$i]);
                $manager->flush();
            }
        }
       
        

        return $this->redirectToRoute('liste_annee', [
            'id' => $id
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
