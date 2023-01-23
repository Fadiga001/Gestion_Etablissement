<?php

namespace App\Controller;

use DateTime;
use App\Entity\Noter;
use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Form\ClasseType;
use App\Form\semestreType;
use App\Form\NoteEtudiantType;
use App\Entity\AnneeAcademique;
use App\Repository\NoterRepository;
use App\Repository\ClasseRepository;
use App\Repository\EtudiantRepository;
use App\Repository\MatieresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnneeAcademiqueRepository;
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

    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}', name: 'details_classes')]
    public function detailsClasse(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, Classe $classe, $id, ClasseRepository $classeRepo): Response
    {

        $etudiant = $etudiantRepo->listeEtudiantDuneClasseEtAnnee($id);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->listeMatieresParClasse($id);
       
       

        return $this->render('classes/detailsClasse.html.twig', [
            'etudiant' => $etudiant,
            'classe' => $classe,
            'matiere'=> $matiere,
        ]);
    }

    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/{idMat}', name: 'consulter_note')]
    public function Consulter(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, NoterRepository $noteRepo, Classe $classe, $id, $idMat, ClasseRepository $classeRepo, Request $request): Response
    {

        $etudiant = $etudiantRepo->listeEtudiantDuneClasseEtAnnee($id);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->findOneById($idMat);

        $form = $this->createForm(semestreType::class);
        $form->handleRequest($request);

        $listenote = [];
        $semestre = [];
        if($form->isSubmitted() && $form->isValid()){
            $semestre = $form->getData();
            $listenote = $noteRepo->listeNote($semestre);
         
        }

       

        return $this->render('classes/consulterNote.html.twig', [
            'etudiant' => $etudiant,
            'classe' => $classe,
            'matiere'=> $matiere,
            'id'=>$id,
            'idMat'=>$idMat,
            'listenote' => $listenote,
            'form' => $form->createView(),
            'semestre' => $semestre

        ]);
    }

    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/{idMat}/{matricule}/edit', name: 'edit_note')]
    #[ParamConverter('noter', options: ['mapping' => ['matricule' => 'etudiants']])]
    public function edit(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, NoterRepository $noteRepo, Classe $classe, $id, $idMat, $matricule, ClasseRepository $classeRepo, Request $request): Response
    {

        $etudiant = $etudiantRepo->findOneByMatricule($matricule);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->findOneById($idMat);
        $listeNote = $noteRepo->listeNoteParEtudiant($matricule,$matiere->getDenomination());
        $mat = $noteRepo->findOneByEtudiants($matricule);

        $typeEvaluation = [];

        $note1 = [];

        if($request->isMethod('post')){
            
           $typeEvaluation = $request->get('typeEvaluation');

           $note1 = [];

           for($i=0; $i<sizeof($listeNote); $i++){

                if($listeNote[$i]->getEtudiants() == $matricule && $listeNote[$i]->getMatieres() == $matiere && $listeNote[$i]->getTypeEvaluation() == $typeEvaluation && $listeNote[$i]->getClasses() == $classe->getDenomination()){
                    
                    $note1 = $noteRepo->editNote($matricule, $matiere->getDenomination(), $typeEvaluation);
                    
                }

           }

           if(!$note1){
                $this ->addFlash('danger', "Aucune note n'existe pour ce type d'évaluation($typeEvaluation)");

                return $this->redirectToRoute('edit_note', ['id'=>$id, 'idMat'=>$idMat, 'matricule'=>$matricule]);
           }
         
              
        }


        return $this->render('classes/editNote.html.twig', [
            'etudiant' => $etudiant,
            'classe' => $classe,
            'matiere'=> $matiere,
            'id'=>$id,
            'note'=>$note1,
            'idMat'=>$idMat,
            'typeEvaluation' =>$typeEvaluation,
            'matricule'=>$matricule,
            'mat'=>$mat

        ]);
    }


    



    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/{idMat}/{matricule}/{evaluation}/noter', name: 'modifier_note')]
    public function modifierNote(NoterRepository $noteRepo, MatieresRepository $matRepo, Classe $classe, $id, $idMat, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo,$matricule, $evaluation, Request $request, EntityManagerInterface $manager): Response
    {

        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->findOneById($idMat);
        $note = $noteRepo->editNote($matricule,$matiere->getDenomination(),$evaluation);
        $mat = $noteRepo->findOneByEtudiants($matricule);

        $form = $this->createForm(NoteEtudiantType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $noteEtudiant = $form->get('note')->getData();
            $note->setNoteEtudiant($noteEtudiant);
            $manager->persist($note);
            $manager->flush();
            
            $this->addFlash('success', "Note modifiée avec succès");

            return $this->redirectToRoute('consulter_note', ['id'=>$id, 'idMat'=>$idMat ]);
        }


       
        return $this->render('classes/noteModifiee.html.twig', [
            'form'=>$form->createView(),
            'classe' => $classe,
            'matiere'=> $matiere,
            'id'=>$id,
            'note'=>$note,
            'idMat'=>$idMat,
            'evaluation' =>$evaluation,
            'matricule'=>$matricule,
            'mat'=>$mat
        ]);
    }




    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/{idMat}/noter', name: 'donner_note')]
    public function donnerNote(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, Classe $classe, $id, $idMat, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo, Request $request, NoterRepository $noteRepo, EntityManagerInterface $manager): Response
    {

        $etudiant = $etudiantRepo->listeEtudiantDuneClasseEtAnnee($id);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->findOneById($idMat);
        $anneeActive = $anneeRepo->findOneByActive(true);
        

        $an = new DateTime();
     
        $evaluation = [];

        if($request->isMethod('post'))
        {
            $notes = $request->get('note');
            $evaluation = $request->get('typeEvaluation');

            if($anneeActive->getDebut() <= $an && $anneeActive->getFinPremierSemestre() >= $an)
            {
                for($i=0; $i<sizeof($etudiant); $i++)
                {

                    $note = new Noter;

                    $mat = $etudiant[$i]->getMatricule();
                    $noteEtud = $notes[$i];

                    if($noteEtud > 20 || $noteEtud < 0)
                    {
                        $this ->addFlash('danger', 'Les notes doivent être comprises entre 0-20');

                        return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

                    }else{
                        $note->setEtudiants($mat)
                        ->setClasses($classe)
                        ->setMatieres($matiere)
                        ->setSemestre('PREMIER SEMESTRE')
                        ->setAnnee($anneeActive->getAnneeScolaire())
                        ->setProf($matiere->getProf())
                        ->setNoteEtudiant($noteEtud);

                        if($evaluation == 'Note de classe')
                        {
                            $note->setTypeEvaluation($evaluation);
                        }else{
                            $note->setTypeEvaluation($evaluation.'(Premier Semestre)');
                        }

                        $manager->persist($note);
                    }

                }


                $manager->flush();

                $this ->addFlash('success', 'Les notes ont été attribuées avec succès');    
                
                return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);
                

            }else if($anneeActive->getFinPremierSemestre() <= $an && $anneeActive->getFin() >= $an)
            {

                for($i=0; $i<sizeof($etudiant); $i++)
                {

                    $note = new Noter;

                    $mat = $etudiant[$i]->getMatricule();
                    $noteEtud = $notes[$i];

                    if($noteEtud > 20 || $noteEtud < 0)
                    {
                        $this ->addFlash('danger', 'Les notes doivent être comprises entre 0-20');

                        return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

                    }else{
                        $note->setEtudiants($mat)
                        ->setClasses($classe)
                        ->setMatieres($matiere)
                        ->setSemestre('DEUXIEME SEMESTRE')
                        ->setAnnee($anneeActive->getAnneeScolaire())
                        ->setProf($matiere->getProf())
                        ->setNoteEtudiant($noteEtud);

                        if($evaluation == 'Note de classe')
                        {
                            $note->setTypeEvaluation($evaluation);
                        }else{
                            $note->setTypeEvaluation($evaluation.'(Deuxième Semestre)');
                        }

                        $manager->persist($note);
                    }

                }

                $manager->flush();

                $this ->addFlash('success', 'Les notes ont été attribuées avec succès');    
                
                return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

            }

        }

        


       
        return $this->render('classes/donnerNote.html.twig', [
            'etudiant' => $etudiant,
            'classe' => $classe,
            'matiere'=> $matiere,
            'anneeActive' => $anneeActive,
            'id'=>$id, 
            'idMat'=>$idMat
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
