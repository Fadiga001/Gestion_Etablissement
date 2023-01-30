<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Form\searchFormType;
use App\Form\ReinscriptionType;
use App\Repository\NoterRepository;
use App\Services\paginationServices;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnneeAcademiqueRepository;
use App\Repository\ClasseRepository;
use App\Repository\MatieresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant/liste-des/differents-etudiants/{page<\d+>?1}', name: 'liste_etudiants')]
    #[IsGranted("ROLE_USER")]
    public function index(paginationServices $pagination, Request $request, $page=1): Response
    {

       $pagination->setEntityClass(Etudiant::class)
                  ->setPage($page);
    
        return $this->render('etudiant/listeEtudiant.html.twig', [
            'pagination'=>$pagination
        ]);

    }



    #[Route('/inscrire/etudiants', name: 'inscrire_etudiants', methods: ['GET', 'POST'])]
    public function new(Request $request, EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->add($etudiant, true);

            $this->addFlash('success', 'Etudiant inscrit avec succès.');

            return $this->redirectToRoute('liste_etudiants', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiant/inscrireEtudiant.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }



    #[Route('/inscrire/etudiants/classeReinscrite', name: 'classe_reinscrite')]
    public function classeReinscrite(Request $request, EtudiantRepository $etudiantRepository, AnneeAcademiqueRepository $anneeRepo, NoterRepository $noteRepo, MatieresRepository $matieresRepo): Response
    {

        $anneeActive = $anneeRepo->findOneByActive(true);
        $form = $this->createForm(searchFormType::class);
        $form->handleRequest($request);

        $listeNote = $noteRepo->findAll();
        $listeEtudiant= [];
        $classe = [];
        $listeMat =[];



        if ($form->isSubmitted() && $form->isValid()) {

            $classe = $form->get('codeClasse')->getData();

            $listeEtudiant = $etudiantRepository->classeAReinscrire($classe->getCodeClasse());

            $listeMat = $matieresRepo->MatieresParClasse($classe->getCodeClasse());


        }


        

        return $this->renderForm('etudiant/classeReinscrire.html.twig', [
            'form' => $form,
            'etudiant' => $listeEtudiant,
            'classe' =>$classe,
            'anneeActive' => $anneeActive,
            'listeNote' => $listeNote,
            'listeMat'=>$listeMat,
            'totalMat'=> sizeof($listeMat),

        ]);
    }


    #[Route('/inscrire/etudiants/classeReinscrite/{classe}/{matricule}/valider', name: 'etudiant_reinscrit')]
    #[ParamConverter('etudiant', options: ['mapping' => ['matricule' => 'matricule']])]
    public function Reinscription( Request $request, EtudiantRepository $etudiantRepository, AnneeAcademiqueRepository $anneeRepo, NoterRepository $noteRepo, ClasseRepository $classeRepo, $matricule,$classe, EntityManagerInterface $manager): Response
    {

        $classes = $classeRepo->findOneByDenomination($classe);
        $anneeActive = $anneeRepo->findOneByActive(true);
        $etudiants = $etudiantRepository->findOneByMatricule($matricule);


        $newEtudiant = new Etudiant;
        $form = $this->createForm(ReinscriptionType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $an = $form->get('anneeScolaire')->getData();
            $clas = $form->get('classe')->getData();

            $newEtudiant->setMatricule($etudiants->getMatricule());
            $newEtudiant->setNom($etudiants->getNom());
            $newEtudiant->setPrenoms($etudiants->getPrenoms());
            $newEtudiant->setDateInscription($etudiants->getDateInscription());
            $newEtudiant->setDateNaissance($etudiants->getDateNaissance());
            $newEtudiant->setLieuNaissance($etudiants->getLieuNaissance());
            $newEtudiant->setPaysNaissance($etudiants->getPaysNaissance());
            $newEtudiant->setSexe($etudiants->getSexe());
            $newEtudiant->setAdresse($etudiants->getAdresse());
            $newEtudiant->setTelephone($etudiants->getTelephone());
            $newEtudiant->setNationalite($etudiants->getNationalite());
            $newEtudiant->setEtablissementDeProvenance($etudiants->getEtablissementDeProvenance());
            $newEtudiant->setPersonneAContacter($etudiants->getPersonneAContacter());
            $newEtudiant->setAdresseDePersonneAContacter($etudiants->getAdresseDePersonneAContacter());
            $newEtudiant->setTelephoneDePersonneAContacter($etudiants->getTelephoneDePersonneAContacter());
            $newEtudiant->setStatus($etudiants->getStatus());
            $newEtudiant->setImageName($etudiants->getImageName());
            $newEtudiant->setAnneeScolaire($an);
            $newEtudiant->setClasse($clas);
            $etudiants->setReinscrire(true);


            $manager->persist($newEtudiant);
            $manager->flush();


            $this->addFlash('success', "L'étudiant a été réinscrire avec succès");
            return $this->redirectToRoute('classe_reinscrite');

        }
        
        


        return $this->renderForm('etudiant/reinscription.html.twig', [
            'form' => $form,
            'etudiants' =>$etudiants
        ]);
    }





    #[Route('/etudiant/liste-des/differents-etudiants/details-etudiants/{id}', name: 'details_etudiant', methods: ['GET'])]
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('etudiant/detailsEtudiant.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }



    #[Route('/etudiant/liste-des/differents-etudiants/details-etudiants/{id}/edit', name: 'edit_etudiant', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->add($etudiant, true);

            $this->addFlash('warning', 'les informations de l\'étudiant ont été modifiées avec succès.');

            return $this->redirectToRoute('details_etudiant', ['id'=> $etudiant->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiant/editEtudiant.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }


    
}
