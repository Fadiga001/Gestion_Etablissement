<?php

namespace App\Controller;

use App\Entity\Professeurs;
use App\Form\ProfesseursType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfesseursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfesseursController extends AbstractController
{
    #[Route('/professeurs', name: 'liste_prof')]
    #[IsGranted("ROLE_USER")]
    public function index(ProfesseursRepository $profRepo): Response
    {

        $prof = $profRepo->findAll();

        return $this->render('professeurs/liste_prof.html.twig', [
            'prof' => $prof,
        ]);
    }


    #[Route('/professeurs/creer_professeurs', name: 'creer_prof')]

    public function creer_prof( Request $request, EntityManagerInterface $entityManager): Response
    {

        $profs = new Professeurs();
       $form = $this->createForm(ProfesseursType::class, $profs);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
           $profs = $form->getData();
           $entityManager->persist($profs);
           $entityManager->flush();

           $this->addFlash('success', 'Professeur enregistré avec succès !');

           return $this->redirectToRoute("liste_prof");
       }

        return $this->render('professeurs/creer_prof.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/professeurs/modifier_professeurs/{nom}', name: 'edit_prof')]

    public function edit_prof(Professeurs $profs, Request $request, EntityManagerInterface $entityManager): Response
    {

       $form = $this->createForm(ProfesseursType::class, $profs);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
           $profs = $form->getData();
           $entityManager->persist($profs);
           $entityManager->flush();

           $this->addFlash('success', 'Professeur modifié avec succès !');

           return $this->redirectToRoute("liste_prof");
       }

        return $this->render('professeurs/edit.html.twig', [
            'form' => $form->createView(),
            'prof'=> $profs
        ]);
    }


    #[Route('/professeurs/delete_professeurs/{nom}', name: 'delete_prof')]
    public function supprimer(Professeurs $prof, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($prof);
        $entityManager->flush();

        $this->addFlash('danger', 'Les données de ce professeur ont été supprimées avec succès !');

        return $this->redirectToRoute('liste_prof');
       
    }
}
