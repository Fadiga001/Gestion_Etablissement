<?php

namespace App\Controller;

use App\Form\searchFormType;
use App\Repository\EtudiantRepository;
use App\Repository\AnneeAcademiqueRepository;
use App\Repository\ClasseRepository;
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


    #[Route('/impression/{matricule}/{classe}', name: 'bulletin_impression')]
    public function bulletinSemestre1(AnneeAcademiqueRepository $anneeRepo, EtudiantRepository $etudiantRepo, NoterRepository $noteRepo, ClasseRepository $classeRepo, $matricule, $classe ): Response
    {
        $anneeActive = $anneeRepo->findOneByActive(true);
        $note = $noteRepo->NoteParEtudiant($matricule, 'PREMIER SEMESTRE');
        $matProfs = $noteRepo->typeMatiere('MATIERES PROFESSIONNELLES');
        $matGen = $noteRepo->typeMatiere('MATIERES GENERALES');
        $matArt = $noteRepo->typeMatiere('MATIERES ARTISTIQUES');

        $etudiant = $etudiantRepo->findOneByMatricule($matricule);
        $classes = $classeRepo->findOneByCodeClasse($classe);


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
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        //On génère un nom de fichier
        $fichier = 'bulletin-premier-semestre'. $etudiant->getMatricule() . $etudiant->getNom(). $etudiant->getPrenoms(). '.pdf';

        //On envoie le pdf au navigateur
        $dompdf->stream($fichier, [
            'Attachment' =>true
        ]);

        
        return new Response();
    }
}
