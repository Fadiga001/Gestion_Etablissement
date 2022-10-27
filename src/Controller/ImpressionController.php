<?php

namespace App\Controller;



use App\Form\noteSearchFormType;
use App\Repository\EtudiantRepository;
use App\Services\pdfServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImpressionController extends AbstractController
{
    #[Route('/impression', name: 'app_impression')]
    public function index(EtudiantRepository $etudiantRepo, Request $request): Response
    {

        $form = $this->createForm(noteSearchFormType::class);
        $form->handleRequest($request);

        $etudiants = [];

        if($form->isSubmitted() && $form->isValid())
        {
            $criteria = $form->getData();

            $etudiants = $etudiantRepo->searchStudents($criteria);
        }

        return $this->render('impression/premierSemestre.html.twig', [
            'etudiant'=> $etudiants,
            'form'=>$form->createView()
        ]);
    }

    #[Route('/impression/liste-classe', name: 'listeClasseImprimee')]
    public function imprimerListe()
    {

    }
}
