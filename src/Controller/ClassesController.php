<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Noter;
use App\Entity\Classe;
use App\Entity\Moyenne;
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


        return $this->render('classes/detailsClasse.html.twig', [
            'etudiant' => $etudiant,
            'classe' => $classe,
            'matiere'=> $matiere,
        ]);
    }



    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/listeClasse', name: 'listeClasseEtudiant')]
    public function listeClasse(EtudiantRepository $etudiantRepo, Classe $classe, $id, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo, EntityManagerInterface $manager): Response
    {

        $etudiant = $etudiantRepo->listeEtudiantDuneClasseEtAnnee($id);
        $classe = $classeRepo->findOneById($id);
        $anneeActive = $anneeRepo->findOneByActive(true);

        arsort($etudiant);

        $new_etudiant = array();
        $index_new_values = 1;
        foreach($etudiant as $cle => $valeur) {
	        $new_etudiant[$index_new_values] = $etudiant[$cle];
	        $index_new_values++;
        }



        //On definie les options du PDF
        $pdfOptions = new Options();

        //On definie la police par defaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        //On instancie le DOMPDF
        $dompdf = new Dompdf();
        $context = stream_context_create([
            'ssl'=> [
                'verify_peer'=>FALSE,
                'verify_peer_name'=>FALSE,
                'allow_self_signed'=>True
            ]
        ]);

        $dompdf->setHttpContext($context);

        //On génère le html
        $html = $this->renderView('classes/listeParClasse.html.twig', [

            'etudiant'=>$new_etudiant,
            'classe'=>$classe,
            'anneeActive'=>$anneeActive,
            
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        //On génère un nom de fichier
        $fichier = 'liste-classe-'.$classe->getCodeClasse().'.pdf';

        //On envoie le pdf au navigateur
        $dompdf->stream($fichier, [
            'Attachment' =>true
        ]);

        
        return new Response();
    }




    #[Route('/classes/voir-les-differentes-classes/details-classe/{id}/{idMat}', name: 'consulter_note')]
    public function Consulter(EtudiantRepository $etudiantRepo, MatieresRepository $matRepo, NoterRepository $noteRepo, Classe $classe, $id, $idMat, ClasseRepository $classeRepo,AnneeAcademiqueRepository $anneeRepo, Request $request): Response
    {

        $anneeActive= $anneeRepo->findOneByActive(true);
        $etudiant = $etudiantRepo->listeEtudiantDuneClasseEtAnnee($id);
        $classe = $classeRepo->findOneById($id);
        $matiere = $matRepo->findOneById($idMat);

        $form = $this->createForm(semestreType::class);
        $form->handleRequest($request);

        $listenote = [];
        $semestre = [];
        if($form->isSubmitted() && $form->isValid()){
            $semestre = $form->getData();
            $listenote = $noteRepo->listeNote($semestre, $anneeActive->getAnneeScolaire(),$classe->getDenomination());
         
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
        $mat = $noteRepo->findOneByMatricules($matricule);

     



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
                    $notePartiels =  $notePartiel[$i];

                    $moy = ($noteClasses+$notePartiels)/2 ;

                    if(empty($listeNote))
                    {
                        if($noteClasses > 20 || $noteClasses < 0 || $notePartiels > 20 || $notePartiels < 0)
                            {
                                $this ->addFlash('danger', 'Les notes doivent être comprises entre 0-20');

                                return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

                            }else{

                                $note->setMatricules($etudiant[$i])
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
                    }else{

                        for($j = 0; $j<sizeof($listeNote); $j++)
                        {
                            if($listeNote[$j]->getClasses() == $classe && $listeNote[$j]->getMatiere() == $matiere && $listeNote[$j]->getAnnee()== $anneeActive->getAnneeScolaire() && $listeNote[$j]->getSemestre() == 'PREMIER SEMESTRE' )
                            {
                                $this ->addFlash('danger', 'Cette matière a déjà été notée donc vous pouvez modifier les notes dans la session consulter');

                                return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);
                            }else{

                                if($noteClasses > 20 || $noteClasses < 0 || $notePartiels > 20 || $notePartiels < 0)
                                {
                                    $this ->addFlash('danger', 'Les notes doivent être comprises entre 0-20');

                                    return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

                                }else{

                                    $note->setMatricules($etudiant[$i])
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

                        }
                        
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
                    $noteClasses =(float) $noteClasse[$i];
                    $notePartiels = (float) $notePartiel[$i];

                    $moy = (float) ($noteClasses+$notePartiels)/2 ;

                    if(empty($listeNote))
                    {
                        if($noteClasses > 20 || $noteClasses < 0 || $notePartiels > 20 || $notePartiels < 0)
                            {
                                $this ->addFlash('danger', 'Les notes doivent être comprises entre 0-20');

                                return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

                            }else{

                                $note->setMatricules($etudiant[$i])
                                ->setClasses($classe)
                                ->setMatiere($matiere)
                                ->setSemestre('DEUXIEME SEMESTRE')
                                ->setAnnee($anneeActive->getAnneeScolaire())
                                ->setProf($matiere->getProf())
                                ->setNoteClasse($noteClasses)
                                ->setNotePartiel($notePartiels)
                                ->setMoyenne($moy);

                                $manager->persist($note);
                            }
                    }else{

                        for($j = 0; $j<sizeof($listeNote); $j++)
                        {
                            if($listeNote[$j]->getClasses() == $classe && $listeNote[$j]->getMatiere() == $matiere && $listeNote[$j]->getAnnee()== $anneeActive->getAnneeScolaire() && $listeNote[$j]->getSemestre() == 'DEUXIEME SEMESTRE' )
                            {
                                $this ->addFlash('danger', 'Cette matière a déjà été notée donc vous pouvez modifier les notes dans la session consulter');

                                return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);
                            }else{

                                if($noteClasses > 20 || $noteClasses < 0 || $notePartiels > 20 || $notePartiels < 0)
                                {
                                    $this ->addFlash('danger', 'Les notes doivent être comprises entre 0-20');

                                    return $this->redirectToRoute('donner_note', ['id'=>$id, 'idMat'=>$idMat]);

                                }else{

                                    $note->setMatricules($etudiant[$i])
                                    ->setClasses($classe)
                                    ->setMatiere($matiere)
                                    ->setSemestre('DEUXIEME SEMESTRE')
                                    ->setAnnee($anneeActive->getAnneeScolaire())
                                    ->setProf($matiere->getProf())
                                    ->setNoteClasse($noteClasses)
                                    ->setNotePartiel($notePartiels)
                                    ->setMoyenne($moy);

                                    $manager->persist($note);
                                }
                            
                            }

                        }
                        
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
