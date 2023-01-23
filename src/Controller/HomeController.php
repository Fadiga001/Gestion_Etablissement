<?php

namespace App\Controller;

use App\Repository\AnneeAcademiqueRepository;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Repository\GalerieRepository;
use App\Repository\EtudiantRepository;
use App\Repository\ProfesseursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    #[Route('/school-save-acceuil', name: 'app_home')]
    #[IsGranted("ROLE_USER")]
    public function index(AnneeAcademiqueRepository $anneeRepo, GalerieRepository $galerieRepository, Request $request, EtudiantRepository $etudiantRepo,  UserRepository $userRepo, ClasseRepository $classeRepo, ProfesseursRepository $professeursRepos, SessionInterface $session): Response
    {

       

        $lengthEtudiant = $etudiantRepo->listeEtudiantDuneAnnee();


        $lengthUser = $userRepo->findAll();
        $lengthClasse = $classeRepo->findAll();
        $lengthProfs = $professeursRepos->findAll();
        $allImage = $galerieRepository->findAllImages();



        $etudiant = $etudiantRepo->listeEtudiantDuneAnnee();

        return $this->render('home/index.html.twig', [
          'lengthEtudiant'=> $lengthEtudiant,
          'lengthUser'=> $lengthUser,
          'lengthClasse'=>$lengthClasse,
          'lengthProfs'=>$lengthProfs,
          'etudiant'=> $etudiant,
          'allImage'=> $allImage, 

        ]);
    }
}
