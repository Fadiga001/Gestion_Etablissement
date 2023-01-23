<?php

namespace App\Controller;

use App\Entity\Matieres;
use App\Form\MatiereType;
use App\Repository\MatieresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MatieresController extends AbstractController
{
    #[Route('/matieres', name: 'liste_matieres')]
    #[IsGranted("ROLE_USER")]
    public function index(MatieresRepository $matieresRepo): Response
    {

        $matieres = $matieresRepo->findAll();
        return $this->render('matieres/liste_matieres.html.twig', [
            'matieres' => $matieres,
        ]);
    }

    #[Route('/matieres/creation_de_matieres', name: 'creer_matieres')]
    public function crerMatiere(Request $request, EntityManagerInterface $entityManager): Response
    {

        $matiere = new Matieres();

        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $matiere = $form->getData();
            if($matiere->getCoefficient() < 0 || $matiere->getCoefficient() > 10)
            {
                $this->addFlash('danger', 'le coefficient doit être compris entre 0-10');
           
                return $this->redirectToRoute('creer_matieres');
            }else{

                $entityManager->persist($matiere);
                $entityManager->flush();
    
                $this->addFlash('success', 'Matière créée avec succès.');
    
                return $this->redirectToRoute("liste_matieres");
            }
            
        }
     
        return $this->render('matieres/creerMatiere.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route('/matieres/modification_de_matieres/{denomination}', name: 'editer_matieres')]
    public function editMatiere(Matieres $matiere, Request $request, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $matiere = $form->getData();
            if($matiere->getCoefficient() < 0 || $matiere->getCoefficient() > 10)
            {
                $this->addFlash('danger', 'le coefficient doit être compris entre 0-10');
           
                return $this->redirectToRoute('editer_matieres');
            }else{
                
                $entityManager->persist($matiere);
                $entityManager->flush();
    
                $this->addFlash('success', 'Matière modifiée avec succès.');
    
                return $this->redirectToRoute("liste_matieres");
            }
        }
     
        return $this->render('matieres/editMatiere.html.twig', [
            'form'=> $form->createView(),
            'matiere'=>$matiere
        ]);
    }


    #[Route('/matieres/modification_matiere_par/{denomination}', name: 'delete_matiere')]
    public function supprimer(Matieres $matiere, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($matiere);
        $entityManager->flush();

        $this->addFlash('danger', 'La matiére a été supprimée avec succès !');

        return $this->redirectToRoute('liste_matieres');
       
    }
}
