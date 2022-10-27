<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Form\FiliereType;
use App\Repository\FiliereRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FiliereController extends AbstractController
{
    #[Route('/filiere', name: 'liste_filiere')]
    #[IsGranted("ROLE_USER")]
    public function index(FiliereRepository $filiereRepo): Response
    {

        $listeFiliere = $filiereRepo->findAll();
        return $this->render('filiere/listeFiliere.html.twig', [
            'filiere' => $listeFiliere,
        ]);
    }

    #[Route('/filiere/Creation_De_Filiere', name: 'creerfiliere')]
    public function CreerFiliere(Request $request, EntityManagerInterface $manager): Response
    {

        $filiere = new Filiere();
        $form = $this->createForm(FiliereType::class, $filiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $filiere = $form->getData();
            $manager->persist($filiere);
            $manager->flush();

            $this->addFlash('success', 'La filière a été créée avec succès');

            return $this->redirectToRoute("liste_filiere");
        }
     
        return $this->render('filiere/creerFiliere.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    #[Route('/filiere/Modification-De-Filiere/{denomination}', name: 'editerFiliere')]
  
    public function EditerFiliere(Filiere $filiere, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(FiliereType::class, $filiere);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid())
        {
            $filiere = $form->getData();
            $manager->persist($filiere);
            $manager->flush();

            $this->addFlash('success', 'La filière a été modifiée avec succès');

            return $this->redirectToRoute("liste_filiere");
        }
     
        return $this->render('filiere/editerFiliere.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    #[Route('/filiere/suppresion-d-une-filière/{denomination}', name: 'deleteFiliere')]
    public function deleteFiliere(Filiere $filiere, EntityManagerInterface $manager): Response
    {

        $manager->remove($filiere);
        $manager->flush();

        $this->addFlash('danger', 'Cette filière a été supprimée avec succès');

        return $this->redirectToRoute('liste_filiere');
    }
}
