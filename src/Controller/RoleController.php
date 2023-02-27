<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/role')]
class RoleController extends AbstractController
{
    #[Route('/', name: 'listes_roles', methods: ['GET'])]
    public function index(RoleRepository $roleRepository): Response
    {
        return $this->render('role/listesRole.html.twig', [
            'roles' => $roleRepository->findAll(),
        ]);
    }

    #[Route('/creer-un-role', name: 'creer_role', methods: ['GET', 'POST'])]
    public function new(Request $request, RoleRepository $roleRepository): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roleRepository->add($role, true);

            return $this->redirectToRoute('listes_roles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('role/creerRole.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/edit', name: 'editer_roles', methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role, RoleRepository $roleRepository): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roleRepository->add($role, true);

            return $this->redirectToRoute('listes_roles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('role/editRole.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete_role', methods: ['POST'])]
    public function delete(Request $request, Role $role, RoleRepository $roleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $roleRepository->remove($role, true);
        }

        return $this->redirectToRoute('listes_roles', [], Response::HTTP_SEE_OTHER);
    }
}
