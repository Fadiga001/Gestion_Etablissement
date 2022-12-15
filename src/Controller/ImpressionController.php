<?php

namespace App\Controller;

use App\Entity\Etudiant;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Services\pdfServices;
use App\Form\noteSearchFormType;
use App\Repository\ClasseRepository;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeAcademiqueRepository;
use App\Repository\NotesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImpressionController extends AbstractController
{
    #[Route('/impression', name: 'app_impression')]
    public function index(Request $request, EtudiantRepository $etudiantRepo, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo): Response
    {

        $form = $this->createForm(noteSearchFormType::class);
        $form->handleRequest($request);

        $etudiants = [];
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

        return $this->render('impression/premierSemestre.html.twig', [
            'etudiant'=> $etudiants,
            'form'=>$form->createView(),
            'classe'=>$classe,
            'annees'=> $annees
        ]);
    }

    #[Route('/impression/{annee}/{idClasse}/liste-classe', name: 'listeClasseImprimee')]
    public function imprimerListe(ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo, EtudiantRepository $etudiantRepo, $annee, $idClasse)
    { 

        $classe = $classeRepo->findOneById($idClasse);
        $annees = $anneeRepo->findOneById($annee);

        $etudiants= $etudiantRepo->listeEtudiantDuneClasseEtAnnee($idClasse, $annee);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
                    
    
        $html = $this->render('impression/imprimerClasse.html.twig', [
            'etudiants' => $etudiants,
            'classe'=> $classe,
            'annees'=> $annees
        ]);
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        ob_get_clean();
        $dompdf->stream('name.pdf', [
            'Attachment'=> false
        ]);
    }


    #[Route('/impression/{id}/{annee}/{idClasse}/liste-classe', name: 'belletinPS')]
    public function bulletin(NotesRepository $noteRepo,$id, ClasseRepository $classeRepo, AnneeAcademiqueRepository $anneeRepo, EtudiantRepository $etudiantRepo, $annee, $idClasse)
    { 
        
        $etudiant = $etudiantRepo->findOneById($id);
        $classe = $classeRepo->findOneById($idClasse);
        $annees = $anneeRepo->findOneById($annee);
        $mat= $etudiantRepo->matricule($id);

        $noteEtudiantMG = $noteRepo->noteEtudiantMG($mat, 'PREMIER SEMESTRE', 'MATIERES GENERALES');
        $noteEtudiantMP = $noteRepo->noteEtudiantMP($mat, 'PREMIER SEMESTRE', 'MATIERES PROFESSIONNELLES');
        $noteEtudiantMA = $noteRepo->noteEtudiantMP($mat, 'PREMIER SEMESTRE', 'MATIERES ARTISTIQUES');
       

        dd($mat);



        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
                    
    
        $html = $this->render('impression/bulletinPremierSemestre.html.twig', [
            'etudiant' => $etudiant,
            'classe'=> $classe,
            'annees'=> $annees,
            'noteEtudiantMG'=> $noteEtudiantMG,
            'noteEtudiantMP' =>$noteEtudiantMP,
            'noteEtudiantMA' => $noteEtudiantMA,

        ]);
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        ob_get_clean();
        $dompdf->stream('name.pdf', [
            'Attachment'=> false
        ]);
    }
}
