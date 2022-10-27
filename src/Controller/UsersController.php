<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'liste_users')]
    public function index(UserRepository $userRepo): Response
    {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $userRepo->findAll();
        return $this->render('users/listeUsers.html.twig', [
            'user' => $users,
        ]);
    }


    #[Route('/users/modifier-les -donnees-des-utilisateur/{id}', name: 'edit_user')]
    public function editerClasse(User $users, Request $request, EntityManagerInterface $manager): Response
    {


        $form = $this->createForm(UserType::class, $users);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $users = $form->getData();
            $manager->persist($users);
            $manager->flush();

            $this->addFlash('success', 'les données utilisateur ont été modifiées avec succès');

            return $this->redirectToRoute('liste_users');
        }

        return $this->render('users/editUser.html.twig', [
            'form'=> $form->createView(),
            'users'=> $users
        ]);
    }


    #[Route('/users/delete-users/{id}', name: 'delete_user')]
    public function supprimer(User $users, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($users);
        $entityManager->flush();

        $this->addFlash('danger', 'L\utilisateur a été supprimée avec succès !');

        return $this->redirectToRoute('liste_users');
       
    }


}
