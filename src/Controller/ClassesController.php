<?php

namespace App\Controller;

use App\Entity\AnneeAcademique;
use App\Entity\Notes;
use App\Entity\Classe;
use App\Form\NotesType;
use App\Entity\Etudiant;
use App\Entity\Matieres;
use App\Form\ClasseType;
use App\Form\NoteType;
use App\Repository\NotesRepository;
use App\Repository\ClasseRepository;
use App\Repository\EtudiantRepository;
use App\Repository\MatieresRepository;
use App\Form\searchEtudiantParAnneeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ClassesController extends AbstractController
{
    #[Route('/classes', name: 'liste_classes')]
    #[IsGranted("ROLE_USER")]
    public function index(ClasseRepository $classe): Response
    {

        $classe = $classe->findAll();

        return $this->render('classes/listeClasse.html.twig', [
            'classe' => $classe,
        ]);
    }

    #[Route('/classes/creer-nouvelle-classe', name: 'creer_classes')]
    public function creerClasse(Request $request, EntityManagerInterface $manager): Response
    {

        $classe = new Classe();

        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $classe = $form->getData();
            $manager->persist($classe);
            $manager->flush();

            $this->addFlash('success', 'Classe créée avec succès');

            return $this->redirectToRoute('liste_classes');
        }

        return $this->render('classes/creerClasse.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route('/classes/modifier-une-classe/{denomination}', name: 'editer_classes')]
    public function editerClasse(Classe $classe, Request $request, EntityManagerInterface $manager): Response
    {


        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $classe = $form->getData();
            $manager->persist($classe);
            $manager->flush();

            $this->addFlash('success', 'Classe a été modifiée avec succès');

            return $this->redirectToRoute('liste_classes');
        }

        return $this->render('classes/editerClasse.html.twig', [
            'form'=> $form->createView(),
            'classe'=> $classe
        ]);
    }

    #[Route('/classes/voir-les-differentes-classes', name: 'voir_classes')]
    public function voirClasse(ClasseRepository $classeRepo, EtudiantRepository $etudiant): Response
    {

        $classes = $classeRepo->findAll();
        $etudiant = $etudiant->findAll();
        return $this->render('classes/voirClasse.html.twig', [
            'classe'=>$classes,
            'etudiant'=> $etudiant
        ]);
    }

    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/', name: 'details_classes')]
    public function detailsClasse( Request $request, EtudiantRepository $etudiant,ClasseRepository $classeRepo, MatieresRepository $mat, Classe $classe, $id, EntityManagerInterface $manager): Response
    {


        

        $searchEtudiantParAnneeForm = $this->createForm(searchEtudiantParAnneeType::class);
        $searchEtudiantParAnneeForm->handleRequest($request);
        $listeEtudiant=[];
        $listeMatieres =[];
    
        if($searchEtudiantParAnneeForm->isSubmitted() && $searchEtudiantParAnneeForm->isValid())
        {
            $annee = $searchEtudiantParAnneeForm->getData();

            $listeEtudiant = $etudiant->listeEtudiantDuneClasse($id,$annee);

            $listeMatieres = $mat->listeMatieresParClasse($id);

                
        }


        return $this->render('classes/detailsClasse.html.twig', [

            'searchForm'=> $searchEtudiantParAnneeForm->createView(),
            'detail'=>$listeEtudiant,
            'classe'=> $classe,
            'listeMatieres'=>$listeMatieres,
 
        ]);
    }


    #[Route('/classes/delete-classe/{denomination}', name: 'delete_classe')]
    public function supprimer(classe $classe, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($classe);
        $entityManager->flush();

        $this->addFlash('danger', 'La classe a été supprimée avec succès !');

        return $this->redirectToRoute('liste_classes');
       
    }
}
