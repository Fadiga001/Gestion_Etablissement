<?php

namespace App\Controller;


use App\Entity\Etudiant;
use App\Form\csvFormType;
use App\Form\EtudiantType;
use App\Form\searchBarType;
use App\Form\searchFormType;
use App\Services\uploadercsv;
use App\Form\ReinscriptionType;
use App\Repository\NoterRepository;
use App\Repository\ClasseRepository;
use App\Services\paginationServices;
use App\Repository\EtudiantRepository;
use App\Repository\MatieresRepository;
use App\Form\searchEtudiantParAnneeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnneeAcademiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant/liste-des/differents-etudiants/{page<\d+>?1}', name: 'liste_etudiants')]
    #[IsGranted('ROLE_USER')]
    public function index(paginationServices $pagination, EtudiantRepository $etudiantRepo, Request $request, $page=1): Response
    {

        $form = $this->createForm(searchBarType::class);
        $form->handleRequest($request);

        $search = [];
        if($form->isSubmitted() && $form->isValid())
        {
          $nom = $form->getData();
          $search = $etudiantRepo->findBySearch($nom);
        }


       $pagination->setEntityClass(Etudiant::class)
                  ->setPage($page);
    
        return $this->render('etudiant/listeEtudiant.html.twig', [
            'pagination'=>$pagination,
            'form'=>$form->createView(),
            'search'=>$search,
        ]);

    }



    #[Route('/inscrire/etudiants', name: 'inscrire_etudiants', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EtudiantRepository $etudiantRepository, SluggerInterface $slugger): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('personne_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $etudiant->setImageFile($newFilename);
            }

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
    #[IsGranted('ROLE_USER')]
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


    


    #[Route('/inscrire/etudiants/classeReinscrite/{classe}/{matricule}/{moyenne}/valider', name: 'etudiant_reinscrit')]
    #[ParamConverter('etudiant', options: ['mapping' => ['matricule' => 'matricule']])]
    #[IsGranted('ROLE_USER')]
    public function Reinscription( Request $request, EtudiantRepository $etudiantRepository, AnneeAcademiqueRepository $anneeRepo, NoterRepository $noteRepo, ClasseRepository $classeRepo, $matricule,$classe, $moyenne, EntityManagerInterface $manager): Response
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
                $newEtudiant->setImageFile($etudiants->getImageFile());
                $newEtudiant->setAnneeScolaire($an);
                $newEtudiant->setClasse($clas);
                $newEtudiant->setClasse($etudiants->getExamensPrepares());
                $etudiants->setReinscrire(true);


                $manager->persist($newEtudiant);
                $manager->flush();


                $this->addFlash('success', "L'étudiant a été réinscrire avec succès");
                return $this->redirectToRoute('classe_reinscrite');

            }
        
        
        


        return $this->renderForm('etudiant/reinscription.html.twig', [
            'form' => $form,
            'etudiants' =>$etudiants,
            'moyenne'=>$moyenne,
        ]);
    }





    #[Route('/etudiant/liste-des/differents-etudiants/details-etudiants/{id}', name: 'details_etudiant', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('etudiant/detailsEtudiant.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }



    #[Route('/etudiant/liste-des/differents-etudiants/details-etudiants/{id}/edit', name: 'edit_etudiant', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('personne_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $etudiant->setImageFile($newFilename);
            }

            $etudiantRepository->add($etudiant, true);

            $this->addFlash('warning', 'les informations de l\'étudiant ont été modifiées avec succès.');

            return $this->redirectToRoute('details_etudiant', ['id'=> $etudiant->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiant/editEtudiant.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }


    #[Route('/etudiant/uploadCSV/', name: 'uploadcsv')]
    #[IsGranted('ROLE_USER')]
    public function UploadCSV(Request $request, uploadercsv $file_uploader, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(csvFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
          $file = $form['upload_file']->getData();

          if ($file) 
          {
            $file_name = $file_uploader->upload($file);
            if (null !== $file_name) // for example
            {
              $directory = $file_uploader->getTargetDirectory();
              $full_path = $directory.'/'.$file_name;
              $fileOpen = fopen($full_path,'r');

              // Do what you want with the full path file...
              // Why not read the content or parse it !!!
            }
            else
            {
              // Oups, an error occured !!!
            }
          }

          
          

        
        }
        
        return $this->render('etudiant/uploadcsv.html.twig', [
            'form'=>$form->createView()
        ]);
    }



    #[Route('/etudiant/delete/{id}', name: 'delete_etudiant')]
    #[IsGranted('ROLE_USER')]
    public function supprimer(Etudiant $etudiant, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($etudiant);
        $entityManager->flush();

        $this->addFlash('danger', 'Etudiant supprimé avec succès');

        return $this->redirectToRoute('liste_etudiants');
       
    }


    
}
