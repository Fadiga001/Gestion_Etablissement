<?php

namespace App\Controller;

use App\Form\searchFormType;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeAcademiqueRepository;
use App\Repository\ClasseRepository;
use App\Repository\MatieresRepository;
use App\Repository\NoterRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImpressionController extends AbstractController
{
    #[Route('/impression', name: 'app_impression')]
    public function index(AnneeAcademiqueRepository $anneeRepo, EtudiantRepository $etudiantRepo, Request $request ): Response
    {
        $anneeActive = $anneeRepo->findOneByActive(true);
        $form = $this->createForm(searchFormType::class);
        $form->handleRequest($request);

        $listeEtudiant= [];
        $classe = [];

        if($form->isSubmitted() && $form->isValid())
        {

            $classe = $form->get('codeClasse')->getData();

            $listeEtudiant = $etudiantRepo->classeAReinscrire($classe->getCodeClasse());
        }

        return $this->render('impression/classeAImprimer.html.twig', [
            'form'=>$form->createView(),
            'etudiant'=>$listeEtudiant,
            'classe'=>$classe,
            'anneeActive'=>$anneeActive,
        ]);
    }


    #[Route('/impression/semestre2', name: 'impression_semestre2')]
    public function semestre2(AnneeAcademiqueRepository $anneeRepo, EtudiantRepository $etudiantRepo, Request $request ): Response
    {
        $anneeActive = $anneeRepo->findOneByActive(true);
        $form = $this->createForm(searchFormType::class);
        $form->handleRequest($request);

        $listeEtudiant= [];
        $classe = [];

        if($form->isSubmitted() && $form->isValid())
        {

            $classe = $form->get('codeClasse')->getData();

            $listeEtudiant = $etudiantRepo->classeAReinscrire($classe->getCodeClasse());
        }

        return $this->render('impression/classeAImprimer2.html.twig', [
            'form'=>$form->createView(),
            'etudiant'=>$listeEtudiant,
            'classe'=>$classe,
            'anneeActive'=>$anneeActive,
        ]);
    }





    #[Route('/impression/{matricule}/{classe}/{anneeActive}', name: 'bulletin_impression')]
    public function bulletinSemestre1(AnneeAcademiqueRepository $anneeRepo, EtudiantRepository $etudiantRepo, NoterRepository $noteRepo, ClasseRepository $classeRepo, MatieresRepository $matieresRepo, $matricule, $classe, $anneeActive ): Response
    {
        $anneeActive = $anneeRepo->findOneByActive(true);
        $matProfs = $noteRepo->NoteParEtudiant($matricule, 'PREMIER SEMESTRE','MATIERES PROFESSIONNELLES');
        $matGen = $noteRepo->NoteParEtudiant($matricule, 'PREMIER SEMESTRE','MATIERES GENERALES');
        $matArt = $noteRepo->NoteParEtudiant($matricule, 'PREMIER SEMESTRE','MATIERES ARTISTIQUES');



        $NoteMatGen = $noteRepo->NoteParTypeMatiere('PREMIER SEMESTRE','MATIERES GENERALES');
        $NoteMatProfs = $noteRepo->NoteParTypeMatiere('PREMIER SEMESTRE','MATIERES PROFESSIONNELLES');


        $etudiant = $etudiantRepo->findOneByMatricule($matricule);
        $classes = $classeRepo->findOneByCodeClasse($classe);

        $etudiantClasse = $etudiantRepo->classeAReinscrire($classe);
        $listeNotes = $noteRepo->listeNote('PREMIER SEMESTRE');
        $listeMat = $matieresRepo->MatieresParClasse($classe);
        $classes = $classeRepo->findOneByCodeClasse($classe);
        $directeur = $classeRepo->DirecteurDeFiliere($classe);


        //Calcul des moyennes par etudiants, par trimestre et le rang de l'étudiant
        $notes = [];
        $moyenne = [];
        for($i=0; $i<sizeof($etudiantClasse); $i++)
        {
            $somCoeffMatGenEtud[$i] = 0;
            $somMoyMatGenEtud[$i] = 0;

            for($j=0; $j<sizeof($NoteMatGen); $j++)
            {

                if($etudiantClasse[$i]->getMatricule() == $NoteMatGen[$j]->getMatricules() && $etudiantClasse[$i]->getClasse() == $NoteMatGen[$j]->getClasses() &&  $NoteMatGen[$j]->getAnnee() == $anneeActive)
                { 
                    $somCoeffMatGenEtud[$i] = $somCoeffMatGenEtud[$i] + $NoteMatGen[$j]->getMatiere()->getCoefficient();
                    $somMoyMatGenEtud[$i] = $somMoyMatGenEtud[$i] + ($NoteMatGen[$j]->getMatiere()->getCoefficient() * $NoteMatGen[$j]->getMoyenne());
                }
            }

            $moyMatGen[$i] = 0;

            if($somCoeffMatGenEtud[$i] == 0)
            {
                $somCoeffMatGenEtud[$i] = 0;
            }else{
                $moyMatGen[$i] = ($somMoyMatGenEtud[$i] / $somCoeffMatGenEtud[$i]);
            }

            


            $somCoeffMatProfsEtud[$i] = 0;
            $somMoyMatProfsEtud[$i] = 0;

            for($j=0; $j<sizeof($NoteMatProfs); $j++)
            {

                if($etudiantClasse[$i]->getMatricule() == $NoteMatProfs[$j]->getMatricules() && $etudiantClasse[$i]->getClasse() == $NoteMatProfs[$j]->getClasses() &&  $NoteMatProfs[$j]->getAnnee() == $anneeActive)
                { 
                    $somCoeffMatProfsEtud[$i] = $somCoeffMatProfsEtud[$i] + $NoteMatProfs[$j]->getMatiere()->getCoefficient();
                    $somMoyMatProfsEtud[$i] = $somMoyMatProfsEtud[$i] + ($NoteMatProfs[$j]->getMatiere()->getCoefficient() * $NoteMatProfs[$j]->getMoyenne());
                }
            }

            $moyMatProf[$i] = 0;

            if($somCoeffMatProfsEtud[$i] == 0)
            {
                $moyMatProf[$i] = 0;

            }else{
                $moyMatProf[$i] = ($somMoyMatProfsEtud[$i] / $somCoeffMatProfsEtud[$i]);
            }


            

            if( $moyMatProf[$i] == 0 || $moyMatGen[$i] == 0)
            {
                $notesEtud[$i] = ($moyMatGen[$i] + $moyMatProf[$i]) / 2 ;
                $etudiantClasse[$i]->setMoyenne(0);
                $notes[$i] = $etudiantClasse[$i] ;
                $moyenne[$i] = 0;
            }else{

                $notesEtud[$i] = ($moyMatGen[$i] + $moyMatProf[$i]) / 2 ;
                $etudiantClasse[$i]->setMoyenne($notesEtud[$i]);
                $notes[$i] = $etudiantClasse[$i];
                $moyenne[$i] = $notesEtud[$i];

            }
            

           
        }

        arsort($moyenne);
        

        $values_new = array();
        $index_new_values = 1;
        foreach($moyenne as $cle => $valeur) {
	        $values_new[$index_new_values] = $notes[$cle];
	        $index_new_values++;
        }

  


        // Calcul des moyennes par type de matiere 
        $somCoeffMatGen = 0;
        $somMoyMatGen = 0;
        for($i=0; $i<sizeof($matGen); $i++)
        {
            if($matGen[$i]->getClasses() == $classes->getDenomination() && $matGen[$i]->getAnnee() == $anneeActive)
            {
                $somCoeffMatGen = $somCoeffMatGen + $matGen[$i]->getMatiere()->getCoefficient();

                $somMoyMatGen = $somMoyMatGen + ($matGen[$i]->getMatiere()->getCoefficient() * $matGen[$i]->getMoyenne());
            }
            
        }


        $somCoeffMatProfs = 0;
        $somMoyMatProfs = 0;
        for($i=0; $i<sizeof($matProfs); $i++)
        {
            if($matProfs[$i]->getClasses() == $classes->getDenomination() && $matProfs[$i]->getAnnee() == $anneeActive)
            {
                $somCoeffMatProfs = $somCoeffMatProfs + $matProfs[$i]->getMatiere()->getCoefficient();
                $somMoyMatProfs = $somMoyMatProfs + ($matProfs[$i]->getMatiere()->getCoefficient() * $matProfs[$i]->getMoyenne());
            }
        
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
        $html = $this->renderView('impression/bulletinS1.html.twig', [
            'anneeActive'=>$anneeActive,
            'etudiant'=>$etudiant,
            'classe'=>$classes,
            'matProfs'=>$matProfs,
            'matGen'=>$matGen,
            'matArt'=>$matArt,
            'somCoeffMatGen'=>$somCoeffMatGen,
            'somMoyMatGen'=>$somMoyMatGen,
            'somCoeffmatProfs'=>$somCoeffMatProfs,
            'somMoymatProfs'=>$somMoyMatProfs,
            'etudiantClasse' =>$etudiantClasse,
            'listeNote' => $listeNotes,
            'totalMat' =>sizeof($listeMat),
            'NoteMatGen'=>$NoteMatGen,
            'NoteMatProfs'=>$NoteMatProfs,
            'notes'=>$values_new,
            'anneeActive' => $anneeActive,
            'directeur'=>$directeur,
            'listeMat'=>$listeMat,

        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        //On génère un nom de fichier
        $fichier = 'bulletin-premier-semestre'. $etudiant->getMatricule() . $etudiant->getNom(). $etudiant->getPrenoms(). '.pdf';

        //On envoie le pdf au navigateur
        $dompdf->stream($fichier, [
            'Attachment' =>true
        ]);

        
        return new Response();
    }








    #[Route('/impression/semestre2/{matricule}/{classe}/{anneeActive}', name: 'bulletin_impressionS2')]
    public function bulletinSemestre2(AnneeAcademiqueRepository $anneeRepo, EtudiantRepository $etudiantRepo, NoterRepository $noteRepo, ClasseRepository $classeRepo, MatieresRepository $matieresRepo, $matricule, $classe, $anneeActive ): Response
    {
        $anneeActive = $anneeRepo->findOneByActive(true);
        $matProfs = $noteRepo->NoteParEtudiant($matricule, 'DEUXIEME SEMESTRE','MATIERES PROFESSIONNELLES');
        $matGen = $noteRepo->NoteParEtudiant($matricule, 'DEUXIEME SEMESTRE','MATIERES GENERALES');
        $matArt = $noteRepo->NoteParEtudiant($matricule, 'DEUXIEME SEMESTRE','MATIERES ARTISTIQUES');


        $NoteMatGen = $noteRepo->NoteParTypeMatiere('DEUXIEME SEMESTRE','MATIERES GENERALES');
        $NoteMatProfs = $noteRepo->NoteParTypeMatiere('DEUXIEME SEMESTRE','MATIERES PROFESSIONNELLES');


        $etudiant = $etudiantRepo->findOneByMatricule($matricule);
        $classes = $classeRepo->findOneByCodeClasse($classe);

        $etudiantClasse = $etudiantRepo->classeAReinscrire($classe);
        $listeNotes = $noteRepo->listeNote('DEUXIEME SEMESTRE');
        $listeMat = $matieresRepo->MatieresParClasse($classe);
        $classes = $classeRepo->findOneByCodeClasse($classe);
        $directeur = $classeRepo->DirecteurDeFiliere($classe);

        

        //Calcul des moyennes par etudiants, par trimestre et le rang de l'étudiant
        $notes = [];
        $moyenne = [];
        for($i=0; $i<sizeof($etudiantClasse); $i++)
        {
            $somCoeffMatGenEtud[$i] = 0;
            $somMoyMatGenEtud[$i] = 0;

            for($j=0; $j<sizeof($NoteMatGen); $j++)
            {

                if($etudiantClasse[$i]->getMatricule() == $NoteMatGen[$j]->getMatricules() && $etudiantClasse[$i]->getClasse() == $NoteMatGen[$j]->getClasses() &&  $NoteMatGen[$j]->getAnnee() == $anneeActive)
                { 
                    $somCoeffMatGenEtud[$i] = $somCoeffMatGenEtud[$i] + $NoteMatGen[$j]->getMatiere()->getCoefficient();
                    $somMoyMatGenEtud[$i] = $somMoyMatGenEtud[$i] + ($NoteMatGen[$j]->getMatiere()->getCoefficient() * $NoteMatGen[$j]->getMoyenne());
                }
            }

            $moyMatGen[$i] = 0;

            if($somCoeffMatGenEtud[$i] == 0)
            {
                $somCoeffMatGenEtud[$i] = 0;
            }else{
                $moyMatGen[$i] = ($somMoyMatGenEtud[$i] / $somCoeffMatGenEtud[$i]);
            }

            


            $somCoeffMatProfsEtud[$i] = 0;
            $somMoyMatProfsEtud[$i] = 0;

            for($j=0; $j<sizeof($NoteMatProfs); $j++)
            {

                if($etudiantClasse[$i]->getMatricule() == $NoteMatProfs[$j]->getMatricules() && $etudiantClasse[$i]->getClasse() == $NoteMatProfs[$j]->getClasses() &&  $NoteMatProfs[$j]->getAnnee() == $anneeActive)
                { 
                    $somCoeffMatProfsEtud[$i] = $somCoeffMatProfsEtud[$i] + $NoteMatProfs[$j]->getMatiere()->getCoefficient();
                    $somMoyMatProfsEtud[$i] = $somMoyMatProfsEtud[$i] + ($NoteMatProfs[$j]->getMatiere()->getCoefficient() * $NoteMatProfs[$j]->getMoyenne());
                }
            }

            $moyMatProf[$i] = 0;

            if($somCoeffMatProfsEtud[$i] == 0)
            {
                $moyMatProf[$i] = 0;
            }else{
                $moyMatProf[$i] = ($somMoyMatProfsEtud[$i] / $somCoeffMatProfsEtud[$i]);
            }
            

            if( $moyMatProf[$i] == 0 && $moyMatGen[$i] == 0)
            {
                $notesEtud[$i] = ($moyMatGen[$i] + $moyMatProf[$i]) / 2 ;
                $etudiantClasse[$i]->setMoyenne(0);
                $notes[$i] = $etudiantClasse[$i] ;
                $moyenne[$i]= $notesEtud[$i];
            }else{

                $notesEtud[$i] = ($moyMatGen[$i] + $moyMatProf[$i]) / 2 ;
                $etudiantClasse[$i]->setMoyenne($notesEtud[$i]);
                $notes[$i] = $etudiantClasse[$i] ;
                $moyenne[$i]= $notesEtud[$i];

            }

           
        }

        arsort($moyenne);
        

        $values_new = array();
        $index_new_values = 1;
        foreach($moyenne as $cle => $valeur) {
	        $values_new[$index_new_values] = $notes[$cle];
	        $index_new_values++;
        }



        //Calcul des moyennes générales annuelles des étudiants et leurs rangs

        $listeNotesGenerales = $noteRepo->findAll();
        $moyenneAnnuelles = [];
        
        for($i=0; $i<sizeof($etudiantClasse); $i++)
        {


            // Calcul des sommes de coefficients et de moyennes du premier semestre
            $somCoeffSemestre1[$i] = 0;
            $somMoySemestre1[$i] = 0;

            for($j=0; $j<sizeof($listeNotesGenerales); $j++)
            {

                if($etudiantClasse[$i]->getMatricule() == $listeNotesGenerales[$j]->getMatricules() && $etudiantClasse[$i]->getClasse() == $listeNotesGenerales[$j]->getClasses() &&  $listeNotesGenerales[$j]->getAnnee() == $anneeActive && $listeNotesGenerales[$j]->getSemestre() == 'PREMIER SEMESTRE')
                { 
                    $somCoeffSemestre1[$i] = $somCoeffSemestre1[$i] + $listeNotesGenerales[$j]->getMatiere()->getCoefficient();
                    $somMoySemestre1[$i] = $somMoySemestre1[$i] + ($listeNotesGenerales[$j]->getMatiere()->getCoefficient() * $listeNotesGenerales[$j]->getMoyenne());
                }
            }

            $moySemestre1[$i] = 0;

            if($somCoeffSemestre1[$i] == 0)
            {
                $somCoeffSemestre1[$i] = 0;
            }else{
                $moySemestre1[$i] = ($somMoySemestre1[$i] / $somCoeffSemestre1[$i]);
            }



            // Calcul des sommes de coefficients et de moyennes du deuxième semestre
            $somCoeffSemestre2[$i] = 0;
            $somMoySemestre2[$i] = 0;

            for($j=0; $j<sizeof($listeNotesGenerales); $j++)
            {

                if($etudiantClasse[$i]->getMatricule() == $listeNotesGenerales[$j]->getMatricules() && $etudiantClasse[$i]->getClasse() == $listeNotesGenerales[$j]->getClasses() &&  $listeNotesGenerales[$j]->getAnnee() == $anneeActive && $listeNotesGenerales[$j]->getSemestre() == 'DEUXIEME SEMESTRE')
                { 
                    $somCoeffSemestre2[$i] = $somCoeffSemestre2[$i] + $listeNotesGenerales[$j]->getMatiere()->getCoefficient();
                    $somMoySemestre2[$i] = $somMoySemestre2[$i] + ($listeNotesGenerales[$j]->getMatiere()->getCoefficient() * $listeNotesGenerales[$j]->getMoyenne());
                }
            }

            $moySemestre2[$i] = 0;

            if($somCoeffSemestre2[$i] == 0)
            {
                $somCoeffSemestre2[$i] = 0;
            }else{
                $moySemestre2[$i] = ($somMoySemestre2[$i] / $somCoeffSemestre2[$i]);
            }


            //Calcul des moyennes générales par étudiants
            if( $moySemestre1[$i] == 0 && $moySemestre2[$i] == 0)
            {
                $notesEtud[$i] = ($moySemestre1[$i] + $moySemestre2[$i]) / 2 ;
                $etudiantClasse[$i]->setMoyenne(0);
                $moyenneAnnuelles[$i] = $notesEtud[$i] ;
            }else{

                $notesEtud[$i] = ($moySemestre1[$i] + $moySemestre2[$i]) / 2;
                $etudiantClasse[$i]->setMoyenne($notesEtud[$i]);
                $moyenneAnnuelles[$i] = $notesEtud[$i] ;

            }



        }


        //tri des moyennes annuelles par valeurs
        arsort($moyenneAnnuelles);
        

        //repositionnement des indices des moyennes triées
        $values_new = array();
        $index_new_values = 1;
        foreach($moyenneAnnuelles as $cle => $valeur) {
	        $values_new[$index_new_values] = $notes[$cle];
	        $index_new_values++;
        }  


        


        // Calcul des moyennes par type de matiere 
        $somCoeffMatGen = 0;
        $somMoyMatGen = 0;
        for($i=0; $i<sizeof($matGen); $i++)
        {
            if($matGen[$i]->getClasses() == $classes->getDenomination() && $matGen[$i]->getAnnee() == $anneeActive)
            {
                $somCoeffMatGen = $somCoeffMatGen + $matGen[$i]->getMatiere()->getCoefficient();

                $somMoyMatGen = $somMoyMatGen + ($matGen[$i]->getMatiere()->getCoefficient() * $matGen[$i]->getMoyenne());
            }
            
        }


        $somCoeffmatProfs = 0;
        $somMoymatProfs = 0;
        for($i=0; $i<sizeof($matProfs); $i++)
        {
            if($matProfs[$i]->getClasses() == $classes->getDenomination() && $matProfs[$i]->getAnnee() == $anneeActive)
            {
                $somCoeffmatProfs = $somCoeffmatProfs + $matProfs[$i]->getMatiere()->getCoefficient();
                $somMoymatProfs = $somMoymatProfs + ($matProfs[$i]->getMatiere()->getCoefficient() * $matProfs[$i]->getMoyenne());
            }
           
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
        $html = $this->renderView('impression/bulletinS2.html.twig', [
            'anneeActive'=>$anneeActive,
            'etudiant'=>$etudiant,
            'classe'=>$classes,
            'matProfs'=>$matProfs,
            'matGen'=>$matGen,
            'matArt'=>$matArt,
            'somCoeffMatGen'=>$somCoeffMatGen,
            'somMoyMatGen'=>$somMoyMatGen,
            'somCoeffmatProfs'=>$somCoeffmatProfs,
            'somMoymatProfs'=>$somMoymatProfs,
            'etudiantClasse' =>$etudiantClasse,
            'listeNote' => $listeNotes,
            'totalMat' =>sizeof($listeMat),
            'NoteMatGen'=>$NoteMatGen,
            'NoteMatProfs'=>$NoteMatProfs,
            'notes'=>$values_new,
            'anneeActive' => $anneeActive,
            'directeur'=>$directeur,
            'listeMat'=>$listeMat,

        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        //On génère un nom de fichier
        $fichier = 'bulletin-deuxième-semestre'. $etudiant->getMatricule() . $etudiant->getNom(). $etudiant->getPrenoms(). '.pdf';

        //On envoie le pdf au navigateur
        $dompdf->stream($fichier, [
            'Attachment' =>true
        ]);

        
        return new Response();
    }
}
