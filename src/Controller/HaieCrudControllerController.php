<?php

namespace App\Controller;

use App\Entity\Haie;
use App\Form\Haie1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/haie/crud/controller')]
class HaieCrudControllerController extends AbstractController
{
    #[Route('/', name: 'app_haie_crud_controller_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $haies = $entityManager
            ->getRepository(Haie::class)
            ->findAll();

        return $this->render('haie_crud_controller/index.html.twig', [
            'haies' => $haies,
        ]);
    }

    #[Route('/new', name: 'app_haie_crud_controller_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $haie = new Haie();
        $form = $this->createForm(Haie1Type::class, $haie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($haie);
            $entityManager->flush();

            return $this->redirectToRoute('app_haie_crud_controller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('haie_crud_controller/new.html.twig', [
            'haie' => $haie,
            'form' => $form,
        ]);
    }

    #[Route('/{code}', name: 'app_haie_crud_controller_show', methods: ['GET'])]
    public function show(Haie $haie): Response
    {
        return $this->render('haie_crud_controller/show.html.twig', [
            'haie' => $haie,
        ]);
    }

    #[Route('/{code}/edit', name: 'app_haie_crud_controller_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Haie $haie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Haie1Type::class, $haie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_haie_crud_controller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('haie_crud_controller/edit.html.twig', [
            'haie' => $haie,
            'form' => $form,
        ]);
    }

    #[Route('/{code}', name: 'app_haie_crud_controller_delete', methods: ['POST'])]
    public function delete(Request $request, Haie $haie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$haie->getCode(), $request->request->get('_token'))) {
            $entityManager->remove($haie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_haie_crud_controller_index', [], Response::HTTP_SEE_OTHER);
    }
}
