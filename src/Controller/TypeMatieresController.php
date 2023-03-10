<?php

namespace App\Controller;

use App\Entity\TypeMatieres;
use App\Form\TypeMatiereType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeMatieresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeMatieresController extends AbstractController
{
    #[Route('/type/matieres', name: 'liste_typeMatiere')]
    #[IsGranted('ROLE_USER')]
    public function index(TypeMatieresRepository $typeMatieresRepo): Response
    {

        $TypeMatiere = $typeMatieresRepo->findAll();
        return $this->render('type_matieres/listeTypeMatieres.html.twig', [
            'typeMat' => $TypeMatiere,
        ]);
    }


    #[Route('/type/matieres/creer_type_matiere', name: 'creer_typeMatiere')]
    #[IsGranted('ROLE_USER')]
    public function creerTypeMatiere(Request $request, EntityManagerInterface $entityManager): Response
    {

        $TypeMatiere = new TypeMatieres();

        $form = $this->createForm(TypeMatiereType::class, $TypeMatiere);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $TypeMatiere = $form->getData();
            $entityManager->persist($TypeMatiere);
            $entityManager->flush();

            $this->addFlash('success', 'Type de matiére créé avec succès.');

           return $this->redirectToRoute('liste_typeMatiere');
        }
        
        return $this->render('type_matieres/creerTypeMatiere.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route('/type/matieres/editer_type_matiere/{denomination}', name: 'editer_typeMatiere')]
    #[IsGranted('ROLE_USER')]
    public function edtiterTypeMatiere(TypeMatieres $TypeMatiere, Request $request, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(TypeMatiereType::class, $TypeMatiere);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $TypeMatiere = $form->getData();
            $entityManager->persist($TypeMatiere);
            $entityManager->flush();

            $this->addFlash('success', 'Type de matiére a été modifié avec succès.');

           return $this->redirectToRoute('liste_typeMatiere');
        }
        
        return $this->render('type_matieres/editerTypeMatiere.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route('/matieres/modification_de_type_matiere_par/{denomination}', name: 'delete_typeMatiere')]
    #[IsGranted('ROLE_USER')]
    public function supprimer(TypeMatieres $TypeMatiere, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($TypeMatiere);
        $entityManager->flush();

        $this->addFlash('danger', 'Le type matière a été supprimé avec succès !');

        return $this->redirectToRoute('liste_typeMatiere');
       
    }
}
