<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function editerClasse(User $users, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {


        $form = $this->createForm(UserType::class, $users);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $users = $form->getData();
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
                $users->setImageFile($newFilename);
            }
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
