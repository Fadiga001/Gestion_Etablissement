<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NotesType;
use App\Entity\Etudiant;
use App\Form\noteGroupeeType;
use App\Form\noteSearchFormType;
use App\Repository\NotesRepository;
use App\Repository\ClasseRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnneeAcademiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/notes')]
class NotesController extends AbstractController
{

    #[Route('/', name: 'ajouter_notes', methods: ['GET', 'POST'])]
    public function index(EtudiantRepository $etudiantRepo, Request $request): Response
    {

        $form = $this->createForm(noteSearchFormType::class);
        $form->handleRequest($request);

        $etudiants=[];

        if($form->isSubmitted() && $form->isValid())
        {
            $criteria = $form->getData();

            $etudiants = $etudiantRepo->searchStudents($criteria);
          
        }

        return $this->render('notes/notePerso.html.twig', [
            'form'=> $form->createView(),
            'etudiants'=>$etudiants
        ]);
    }




    #[Route('/{id}/{idClasse}', name: 'donner_note', methods: ['GET', 'POST'])]
    public function show(EtudiantRepository $etudiantRepo, ClasseRepository $classeRepo, $idClasse, $id, Request $request, EntityManagerInterface $manager): Response
    {

        $etudiant = $etudiantRepo->findOneById($id);
        $classe = $classeRepo->findOneById($idClasse);
        $note = new Notes();
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);
        
        

        if($form->isSubmitted() && $form->isValid())
        {
            $notes = $form->getData();
            $manager->persist($notes);
            $manager->flush();
            $this->addFlash(
                'success',
                'Note attribuée avec succès'
            );
            return $this->redirectToRoute('ajouter_notes');
        }

        return $this->render('notes/donnerNote.html.twig', [
            'etudiant'=>$etudiant,
            'classe'=> $classe,
            'form'=> $form->createView()
        ]);
    }



    #[Route('/ajout-de-note-groupee', name: 'note_groupee', methods: ['GET', 'POST'])]
    public function edit(Request $request, EtudiantRepository $etudiantRepo, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo): Response
    {
      
        $form = $this->createForm(noteSearchFormType::class);
        $form->handleRequest($request);

        $etudiants=[];
        $classe = [];
        $annees = [];
        if($request->getMethod()=='POST')
        {
            $clas = $form->get('codeClasse')->getData();
            $annee = $form->get('AnneeScolaire')->getData();
            $classe = $classeRepo->findOneById($clas);
            $annees = $anneeRepo->findOneById($annee);
            
        }

        if($form->isSubmitted() && $form->isValid())
        {
            $criteria = $form->getData();

            $etudiants = $etudiantRepo->searchStudents($criteria);
          
        }

        return $this->render('notes/noteGroupee.html.twig', [
            'form'=> $form->createView(),
            'etudiants'=>$etudiants,
            'classe'=>$classe,
            'annee'=>$annees
        ]);

    }


    #[Route('/ajout-de-note-groupee/{idClasse}/{annee}/Ajouter-Par-Matiere', name: 'noteGroupee_parMatiere', methods: ['GET', 'POST'])]
    public function ajouterNoterGroupee(Request $request, $idClasse, EtudiantRepository $etudiantRepo, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo, $annee, EntityManagerInterface $manager): Response
    {
        $classe = $classeRepo->findOneById($idClasse);
        $annees = $anneeRepo->findOneById($annee);
       

        $etudiants= $etudiantRepo->listeEtudiantDuneClasseEtAnnee($idClasse, $annee);

            $note = new Notes();
            $form = $this->createForm(noteGroupeeType::class, $note);
            $form->handleRequest($request);
            
            if($request->getMethod()=='POST')
            {
                $mat = $request->get('matEtudiant');
                $notes = $request->get('noteEtudiant');
                $note->setEtudiant($mat);
                $note->setNoteEtudiant($notes);
                $manager->persist($note);
                $manager->flush();
            }
        
    
            if($form->isSubmitted() && $form->isValid())
            {
               $note= $form->getData();
               $manager->persist($note);
               $manager->flush();
            }

            
    
        
       

        return $this->render('notes/AjouterNoteGroupee.html.twig', [
            'form'=> $form->createView(),
            'etudiants'=>$etudiants,
            'classe'=>$classe,
            'annees'=>$annees
        ]);

    }





    #[Route('/{id}', name: 'app_notes_delete', methods: ['POST'])]
    public function delete(Request $request, Notes $note, NotesRepository $notesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $notesRepository->remove($note, true);
        }

        return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
    }
}
