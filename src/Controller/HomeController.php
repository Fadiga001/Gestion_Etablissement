<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Services\paginationServices;
use App\Repository\GalerieRepository;
use App\Repository\EtudiantRepository;
use App\Repository\ProfesseursRepository;
use App\Repository\AnneeAcademiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/school-save-acceuil/{page<\d+>?1}', name: 'app_home')]
    #[IsGranted("ROLE_USER")]
    public function index(paginationServices $pagination, GalerieRepository $galerieRepository, EtudiantRepository $etudiantRepo,  UserRepository $userRepo, ClasseRepository $classeRepo, ProfesseursRepository $professeursRepos, $page = 1): Response
    {

       

        $lengthEtudiant = $etudiantRepo->listeEtudiantDuneAnnee();


        $lengthUser = $userRepo->findAll();
        $lengthClasse = $classeRepo->findAll();
        $lengthProfs = $professeursRepos->findAll();
        $allImage = $galerieRepository->findAllImages();



        $pagination->setEntityClass(Etudiant::class)
                    ->setPage($page);

        return $this->render('home/index.html.twig', [
          'lengthEtudiant'=> $lengthEtudiant,
          'lengthUser'=> $lengthUser,
          'lengthClasse'=>$lengthClasse,
          'lengthProfs'=>$lengthProfs,
          'pagination'=>$pagination,
          'allImage'=> $allImage, 

        ]);
    }
}
