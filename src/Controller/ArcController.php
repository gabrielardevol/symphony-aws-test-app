<?php

namespace App\Controller;

use App\Entity\Arc;
use App\Form\ArcType;
use App\Repository\ArcRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/arc')]
final class ArcController extends AbstractController
{
    #[Route(name: 'app_arc_index', methods: ['GET'])]
    public function index(ArcRepository $arcRepository): Response
    {
        return $this->render('arc/index.html.twig', [
            'arcs' => $arcRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_arc_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $arc = new Arc();
        $form = $this->createForm(ArcType::class, $arc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($arc);
            $entityManager->flush();

            return $this->redirectToRoute('app_arc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('arc/new.html.twig', [
            'arc' => $arc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_arc_show', methods: ['GET'])]
    public function show(Arc $arc): Response
    {
        return $this->render('arc/show.html.twig', [
            'arc' => $arc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_arc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Arc $arc, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArcType::class, $arc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_arc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('arc/edit.html.twig', [
            'arc' => $arc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_arc_delete', methods: ['POST'])]
    public function delete(Request $request, Arc $arc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$arc->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($arc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_arc_index', [], Response::HTTP_SEE_OTHER);
    }
}
