<?php

namespace App\Controller;

use App\Entity\Segmento;
use App\Form\SegmentoType;
use App\Repository\SegmentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/segmento')]
class SegmentoController extends AbstractController
{
    #[Route('/', name: 'app_segmento_index', methods: ['GET'])]
    public function index(SegmentoRepository $segmentoRepository): Response
    {
        return $this->render('segmento/index.html.twig', [
            'segmentos' => $segmentoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_segmento_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $segmento = new Segmento();
        $form = $this->createForm(SegmentoType::class, $segmento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($segmento);
            $entityManager->flush();

            return $this->redirectToRoute('app_segmento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('segmento/new.html.twig', [
            'segmento' => $segmento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_segmento_show', methods: ['GET'])]
    public function show(Segmento $segmento): Response
    {
        return $this->render('segmento/show.html.twig', [
            'segmento' => $segmento,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_segmento_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Segmento $segmento, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SegmentoType::class, $segmento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_segmento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('segmento/edit.html.twig', [
            'segmento' => $segmento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_segmento_delete', methods: ['POST'])]
    public function delete(Request $request, Segmento $segmento, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$segmento->getId(), $request->request->get('_token'))) {
            $entityManager->remove($segmento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_segmento_index', [], Response::HTTP_SEE_OTHER);
    }
}
