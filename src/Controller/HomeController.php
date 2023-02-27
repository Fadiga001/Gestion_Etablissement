<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\searchBarType;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Services\paginationServices;
use App\Repository\GalerieRepository;
use App\Repository\EtudiantRepository;
use App\Repository\ProfesseursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/school-save-acceuil/{page<\d+>?1}', name: 'app_home')]
    public function index(paginationServices $pagination, GalerieRepository $galerieRepository, EtudiantRepository $etudiantRepo,  UserRepository $userRepo, ClasseRepository $classeRepo, ProfesseursRepository $professeursRepos, Request $request, $page = 1): Response
    {

       

        $lengthEtudiant = $etudiantRepo->listeEtudiantDuneAnnee();


        $lengthUser = $userRepo->findAll();
        $lengthClasse = $classeRepo->findAll();
        $lengthProfs = $professeursRepos->findAll();
        $allImage = $galerieRepository->findAllImages();

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

        return $this->render('home/index.html.twig', [
          'lengthEtudiant'=> $lengthEtudiant,
          'lengthUser'=> $lengthUser,
          'lengthClasse'=>$lengthClasse,
          'lengthProfs'=>$lengthProfs,
          'pagination'=>$pagination,
          'allImage'=> $allImage, 
          'search'=>$search,
          'form'=>$form->createView(),

        ]);
    }
}
