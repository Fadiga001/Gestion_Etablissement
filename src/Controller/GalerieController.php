<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Form\GalerieType;
use App\Repository\GalerieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/galerie')]
class GalerieController extends AbstractController
{
    #[Route('/', name: 'liste_photo', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function index(GalerieRepository $galerieRepository): Response
    {
      
        return $this->render('galerie/listeImage.html.twig', [
            'galeries' => $galerieRepository->findAll(),
            
        ]);
    }

    #[Route('/ajouter-une-image', name: 'ajouter_image')]
    public function new(Request $request, GalerieRepository $galerieRepository): Response
    {
        $galerie = new Galerie();
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galerieRepository->add($galerie, true);

            
            $this->addFlash(
                'success',
                'Image ajouter avec succès'
            );

            return $this->redirectToRoute('liste_photo', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('galerie/ajouterImage.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'editer_image', methods: ['GET', 'POST'])]
    public function edit(Request $request, Galerie $galerie, GalerieRepository $galerieRepository): Response
    {
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galerieRepository->add($galerie, true);

            return $this->redirectToRoute('liste_photo', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('galerie/editerImage.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete_image', methods: ['POST'])]
    public function delete(Request $request, Galerie $galerie, GalerieRepository $galerieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$galerie->getId(), $request->request->get('_token'))) {
            $galerieRepository->remove($galerie, true);
        }

        return $this->redirectToRoute('liste_photo', [], Response::HTTP_SEE_OTHER);
    }
}
