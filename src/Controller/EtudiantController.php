<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Form\searchFormType;
use App\Services\EtudiantServices;
use App\Repository\EtudiantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant/liste-des/differents-etudiants', name: 'liste_etudiants')]
    #[IsGranted("ROLE_USER")]
    public function index(EtudiantServices $etudiantServices, EtudiantRepository $etudiantRepo, Request $request): Response
    {

        $form = $this->createForm(searchFormType::class);
        $form->handleRequest($request);
        $searching = [];
        if($form->isSubmitted() && $form->isValid())
        {
            $search = $form->getData();
            $searching = $etudiantRepo->findBySearch($search);
        }

        $etudiant = $etudiantServices->getPaginateEtudiant();
        return $this->render('etudiant/listeEtudiant.html.twig', [
            'form'=> $form->createView(),
            'etudiants' => $etudiant,
            'search'=> $searching
        ]);

    }

    #[Route('/inscrire/etudiants', name: 'inscrire_etudiants', methods: ['GET', 'POST'])]
    public function new(Request $request, EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->add($etudiant, true);

            $this->addFlash('success', 'Etudiant inscrit avec succès.');

            return $this->redirectToRoute('liste_etudiants', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiant/inscrireEtudiant.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    #[Route('/etudiant/liste-des/differents-etudiants/details-etudiants/{id}', name: 'details_etudiant', methods: ['GET'])]
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('etudiant/detailsEtudiant.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }

    #[Route('/etudiant/liste-des/differents-etudiants/details-etudiants/{id}/edit', name: 'edit_etudiant', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->add($etudiant, true);

            $this->addFlash('warning', 'les informations de l\'étudiant ont été modifiées avec succès.');

            return $this->redirectToRoute('details_etudiant', ['id'=> $etudiant->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiant/editEtudiant.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    
}
