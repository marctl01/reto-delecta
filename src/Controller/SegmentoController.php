<?php

namespace App\Controller;

use App\Entity\Segmento;
use App\Form\SegmentoType;
use App\Repository\SegmentoRepository;
use App\Service\MetricsCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/segmento')]
class SegmentoController extends AbstractController
{
    #[Route('/', name: 'app_segmento_index', methods: ['GET'])]
    public function index(SegmentoRepository $segmentoRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $segmentos = $segmentoRepository->findAll();

        $pagination = $paginatorInterface->paginate(
            $segmentoRepository->paginationQuery(),
            $request->query->get('page', 1),
            
        );
        return $this->render('segmento/index.html.twig', [
            'pagination' => $pagination
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
    
    #[Route('/recalcular-popularidad/{id}', name: 'recalcular_popularidad', methods: ['GET', 'POST'])]
    public function recalcularPopularidad(int $id, MetricsCalculatorService $metricsCalculatorService, EntityManagerInterface $entityManager): Response
    {
        $segmento = $entityManager->getRepository(Segmento::class)->find($id);
        
        if (!$segmento) {
            throw $this->createNotFoundException('El segmento no existe');
        }
    
        $popularityMedia = $metricsCalculatorService->calcularPopularidadMedia($segmento);
        $segmento->setPopularidadMedia($popularityMedia);
    
        $entityManager->persist($segmento);
        $entityManager->flush();
    
        // Renderizar la plantilla de edición nuevamente con el segmento actualizado y el formulario
        $form = $this->createForm(SegmentoType::class, $segmento);
    
        return $this->render('segmento/edit.html.twig', [
            'segmento' => $segmento,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recalcular-satisfaccion/{id}', name: 'recalcular_satisfaccion', methods: ['GET', 'POST'])]
public function recalcularSatisfaccion(int $id, MetricsCalculatorService $metricsCalculatorService, EntityManagerInterface $entityManager): Response
{
    $segmento = $entityManager->getRepository(Segmento::class)->find($id);
    
    if (!$segmento) {
        throw $this->createNotFoundException('El segmento no existe');
    }

    $satisfaccionMedia = $metricsCalculatorService->calcularSatisfaccionMedia($segmento);
    $segmento->setSatisfaccionMedia($satisfaccionMedia);

    $entityManager->persist($segmento);
    $entityManager->flush();



    // Renderizar la plantilla de edición nuevamente con el segmento actualizado y el formulario
    $form = $this->createForm(SegmentoType::class, $segmento);

    return $this->render('segmento/edit.html.twig', [
        'segmento' => $segmento,
        'form' => $form->createView(),
    ]);
}

#[Route('/recalcular-precio/{id}', name: 'recalcular_precio', methods: ['GET', 'POST'])]
public function recalcularPrecio(int $id, MetricsCalculatorService $metricsCalculatorService, EntityManagerInterface $entityManager): Response
{
    $segmento = $entityManager->getRepository(Segmento::class)->find($id);
    
    if (!$segmento) {
        throw $this->createNotFoundException('El segmento no existe');
    }

    $precioMedio = $metricsCalculatorService->calcularPrecioMedio($segmento);
    $segmento->setAvgPrice($precioMedio);

    $entityManager->persist($segmento);
    $entityManager->flush();


    // Renderizar la plantilla de edición nuevamente con el segmento actualizado y el formulario
    $form = $this->createForm(SegmentoType::class, $segmento);

    return $this->render('segmento/edit.html.twig', [
        'segmento' => $segmento,
        'form' => $form->createView(),
    ]);
}
}
