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
use App\Entity\Moyenne;
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
    public function detailsClasse(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, Classe $classe, $id, ClasseRepository $classeRepo, NoterRepository $noteRepo, EntityManagerInterface $manager): Response
    {

        $etudiant = $etudiantRepo->listeEtudiantDuneClasseEtAnnee($id);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->listeMatieresParClasse($id);
        $listeNote = $noteRepo->findAll();

       
        for($i=0; $i<sizeof($etudiant); $i++)
        {
            $somme = 0;
            $mat = '';
            $classes = '';
            $semestre = '';
            $matricule ='';
            for($j=0; $j<sizeof($listeNote); $j++)
            {
                for($k=0; $k<sizeof($matiere); $k++)
                {
                    if($etudiant[$i]->getMatricule() == $listeNote[$j]->getEtudiants() && $etudiant[$i]->getClasse() == $listeNote[$j]->getClasses() && $etudiant[$i]->getAnneeScolaire() == $listeNote[$j]->getAnnee() && $listeNote[$j]->getSemestre() == 'PREMIER SEMESTRE' && $listeNote[$j]->getMatieres()== $matiere[$k]->getDenomination())
                    {
                        $somme = $somme + $listeNote[$j]->getNoteEtudiant();
                        $mat = $matiere[$k]->getDenomination();
                        $matricule = $etudiant[$i]->getMatricule();
                        $semestre = $listeNote[$j]->getSemestre();
                        $classes = $etudiant[$i]->getClasse();
                    }
                }
                
            }


            $moyenne = ( $somme / 2 );
            
            
            
        }

       

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
    public function edit(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, NoterRepository $noteRepo, Classe $classe, $id, $idMat, $matricule, ClasseRepository $classeRepo, Request $request, EntityManagerInterface $manager): Response
    {

        $etudiant = $etudiantRepo->findOneByMatricule($matricule);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->findOneById($idMat);
        $note = $noteRepo->listeNoteParEtudiant($matricule,$matiere->getDenomination());
        $mat = $noteRepo->findOneByEtudiants($matricule);

     



       $form = $this->createForm(NoteEtudiantType::class);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           $noteClasses = $form->get('noteClasse')->getData();
           $notePartiels = $form->get('notePartiel')->getData();
           $note->setNoteClasse($noteClasses)
                ->setNotePartiel($notePartiels)
                ->setMoyenne( ($noteClasses+$notePartiels) / 2);

                $manager->persist($note);
                $manager->flush();
           
           $this->addFlash('success', "Note modifiée avec succès");

           return $this->redirectToRoute('consulter_note', ['id'=>$id, 'idMat'=>$idMat ]);
       }



        return $this->render('classes/editNote.html.twig', [
            'form'=> $form->createView(),
            'etudiant' => $etudiant,
            'classe' => $classe,
            'matiere'=> $matiere,
            'id'=>$id,
            'idMat'=>$idMat,
            'matricule'=>$matricule,
            'mat'=>$mat,
            'note'=>$note,

        ]);
    }


    



    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/{idMat}/noter', name: 'donner_note')]
    public function donnerNote(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, Classe $classe, $id, $idMat, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo, Request $request, NoterRepository $noteRepo, EntityManagerInterface $manager): Response
    {

        $etudiant = $etudiantRepo->listeEtudiantDuneClasseEtAnnee($id);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->findOneById($idMat);
        $anneeActive = $anneeRepo->findOneByActive(true);
        $listeNote = $noteRepo->findAll();
        

        $an = new DateTime();


        if($request->isMethod('post'))
        {
            $noteClasse = $request->get('noteClasse');
            $notePartiel = $request->get('notePartiel');

            $note = [];

            if($anneeActive->getDebut() <= $an && $anneeActive->getFinPremierSemestre() >= $an)
            {
                for($i=0; $i<sizeof($etudiant); $i++)
                {

                    $note = new Noter;

                    $mat = $etudiant[$i]->getMatricule();
                    $noteClasses = $noteClasse[$i];
                    $notePartiels = $notePartiel[$i];

                    $moy = ($noteClasses+$notePartiels)/2 ;

                    if($noteClasses > 20 || $noteClasses < 0 || $notePartiels > 20 || $notePartiels < 0)
                    {
                        $this ->addFlash('danger', 'Les notes doivent être comprises entre 0-20');

                        return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

                    }else{
                        $note->setEtudiants($mat)
                        ->setClasses($classe)
                        ->setMatiere($matiere)
                        ->setSemestre('PREMIER SEMESTRE')
                        ->setAnnee($anneeActive->getAnneeScolaire())
                        ->setProf($matiere->getProf())
                        ->setNoteClasse($noteClasses)
                        ->setNotePartiel($notePartiels)
                        ->setMoyenne($moy);


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
                    $noteClasses = $noteClasse[$i];
                    $notePartiels = $notePartiel[$i];
                    $moy = ($noteClasses+$notePartiels)/2 ;

                    if($noteClasses > 20 || $noteClasses < 0 || $notePartiels > 20 || $notePartiels < 0)
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
                        ->setNoteClasse($noteClasses)
                        ->setNotePartiel($notePartiels)
                        ->setMoyenne($moy);

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
