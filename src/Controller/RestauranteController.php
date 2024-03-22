<?php

namespace App\Controller;

use App\Entity\Restaurante;
use App\Entity\Segmento;
use App\Form\RestauranteType;
use App\Repository\RestauranteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/restaurante')]
class RestauranteController extends AbstractController
{
    #[Route('/', name: 'app_restaurante_index', methods: ['GET'])]
    public function index(RestauranteRepository $restauranteRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $nombreABuscar = $request->query->get('nombreRestaurante');

        $query = $restauranteRepository->findByNombreRestaurante($nombreABuscar);
        $pagination = $paginatorInterface->paginate(
            $query,
            $request->query->getInt('page', 1),
        );

        return $this->render('restaurante/index.html.twig', [
            'pagination' => $pagination
        ]);
    }


    #[Route('/new', name: 'app_restaurante_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $restaurante = new Restaurante();
        $form = $this->createForm(RestauranteType::class, $restaurante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($restaurante);
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurante_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('restaurante/new.html.twig', [
            'restaurante' => $restaurante,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_restaurante_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Restaurante $restaurante, EntityManagerInterface $entityManager): Response
    {
        $segmentos = $entityManager->getRepository(Segmento::class)->findAll();

        $form = $this->createForm(RestauranteType::class, $restaurante);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_restaurante_edit', ['id' => $restaurante->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('restaurante/edit.html.twig', [
            'restaurante' => $restaurante,
            'form' => $form,
            'segmentos' => $segmentos,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_restaurante_delete', methods: ['POST'])]
    public function delete(Request $request, Restaurante $restaurante, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurante->getId(), $request->request->get('_token'))) {
            $entityManager->remove($restaurante);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_restaurante_index', [], Response::HTTP_SEE_OTHER);
    }
}
