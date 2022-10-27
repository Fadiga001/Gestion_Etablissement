<?php

namespace App\Controller;

use App\Form\searchFormType;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GalerieRepository;
use App\Repository\ProfesseursRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/school-save-acceuil/{anneeScolaire?2021-2022}', name: 'app_home')]
    #[IsGranted("ROLE_USER")]
    public function index(GalerieRepository $galerieRepository, Request $request, EtudiantRepository $etudiantRepo, $anneeScolaire, UserRepository $userRepo, ClasseRepository $classeRepo, ProfesseursRepository $professeursRepos): Response
    {

        $lengthEtudiant = $etudiantRepo->findByYear($anneeScolaire);
        $lengthUser = $userRepo->findAll();
        $lengthClasse = $classeRepo->findAll();
        $lengthProfs = $professeursRepos->findAll();
        $allImage = $galerieRepository->findAllImages();

        $form = $this->createForm(searchFormType::class);
        $form->handleRequest($request);
        $searching = [];

        if($form->isSubmitted() && $form->isValid())
        {
            $search = $form->getData();
            $searching = $etudiantRepo->findBySearch($search);
        }


        $etudiant = $etudiantRepo->findAllStudents();

        return $this->render('home/index.html.twig', [
            'form'=>$form->createView(),
          'lengthEtudiant'=> $lengthEtudiant,
          'lengthUser'=> $lengthUser,
          'lengthClasse'=>$lengthClasse,
          'lengthProfs'=>$lengthProfs,
          'search'=> $searching,
          'etudiant'=> $etudiant,
          'allImage'=> $allImage
        ]);
    }
}
