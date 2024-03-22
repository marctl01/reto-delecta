<?php

namespace App\Controller;

use App\Entity\Restaurante;
use App\Entity\Segmento;
use App\Form\SegmentoType;
use App\Repository\RestauranteRepository;
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

        $nombreABuscar = $request->query->get('nombreSegmento');

        $query = $segmentoRepository->findByNombreSegmento($nombreABuscar);
        $pagination = $paginatorInterface->paginate(
            $query,
            $request->query->getInt('page', 1),
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

    #[Route('/{id}', name: 'app_segmento_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Segmento $segmento, EntityManagerInterface $entityManager, RestauranteRepository $restauranteRepository, PaginatorInterface $paginator): Response
    {
        // Obtener el término de búsqueda del nombre del restaurante
        $nombreABuscar = $request->query->get('nombreRestaurante');
    
        // Obtener todos los restaurantes paginados y filtrados
        $query = $restauranteRepository->findByNombreRestaurante($nombreABuscar);
        $restaurantesPaginados = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Número de página
            10 // Número de elementos por página
        );
    
        // Obtener los restaurantes relacionados con este segmento
        $restaurantesRelacionados = $segmento->getRestaurantes();
    
        $form = $this->createForm(SegmentoType::class, $segmento);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_segmento_edit', ['id' => $segmento->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('segmento/edit.html.twig', [
            'segmento' => $segmento,
            'restaurantes' => $restaurantesPaginados,
            'restaurantes_relacionados' => $restaurantesRelacionados,
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'app_segmento_delete', methods: ['POST'])]
    public function delete(Request $request, Segmento $segmento, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$segmento->getId(), $request->request->get('_token'))) {
            $entityManager->remove($segmento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_segmento_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/recalcular-popularidad/{id}', name: 'recalcular_popularidad', methods: ['GET', 'POST'])]
    public function recalcularPopularidad(int $id, MetricsCalculatorService $metricsCalculatorService, EntityManagerInterface $entityManager, RestauranteRepository $restauranteRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $segmento = $entityManager->getRepository(Segmento::class)->find($id);
        
        if (!$segmento) {
            throw $this->createNotFoundException('El segmento no existe');
        }
    
        // Obtener el término de búsqueda del nombre del restaurante
        $nombreABuscar = $request->query->get('nombreRestaurante');
        
        // Obtener todos los restaurantes paginados y filtrados
        $query = $restauranteRepository->findByNombreRestaurante($nombreABuscar);
        $restaurantesPaginados = $paginatorInterface->paginate(
            $query,
            $request->query->getInt('page', 1), // Número de página
            10 // Número de elementos por página
        );
    
        // Obtener los restaurantes relacionados con este segmento
        $restaurantesRelacionados = $segmento->getRestaurantes();
    
        $popularityMedia = $metricsCalculatorService->calcularPopularidadMedia($segmento);
        $segmento->setPopularidadMedia($popularityMedia);
    
        $entityManager->persist($segmento);
        $entityManager->flush();
    
        // Renderizar la plantilla de edición nuevamente con el segmento actualizado y el formulario
        $form = $this->createForm(SegmentoType::class, $segmento);
    
        return $this->render('segmento/edit.html.twig', [
            'segmento' => $segmento,
            'restaurantes_relacionados' => $restaurantesRelacionados,
            'restaurantes' => $restaurantesPaginados, // Pasar los restaurantes paginados a la plantilla
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/recalcular-satisfaccion/{id}', name: 'recalcular_satisfaccion', methods: ['GET', 'POST'])]
public function recalcularSatisfaccion(int $id, MetricsCalculatorService $metricsCalculatorService, EntityManagerInterface $entityManager, RestauranteRepository $restauranteRepository, PaginatorInterface $paginatorInterface, Request $request): Response
{
    $segmento = $entityManager->getRepository(Segmento::class)->find($id);
    
    if (!$segmento) {
        throw $this->createNotFoundException('El segmento no existe');
    }

    // Obtener el término de búsqueda del nombre del restaurante
    $nombreABuscar = $request->query->get('nombreRestaurante');
    
    // Obtener todos los restaurantes paginados y filtrados
    $query = $restauranteRepository->findByNombreRestaurante($nombreABuscar);
    $restaurantesPaginados = $paginatorInterface->paginate(
        $query,
        $request->query->getInt('page', 1), // Número de página
        10 // Número de elementos por página
    );

    $satisfaccionMedia = $metricsCalculatorService->calcularSatisfaccionMedia($segmento);
    $segmento->setSatisfaccionMedia($satisfaccionMedia);

    $entityManager->persist($segmento);
    $entityManager->flush();

    // Renderizar la plantilla de edición nuevamente con el segmento actualizado y el formulario
    $form = $this->createForm(SegmentoType::class, $segmento);

    return $this->render('segmento/edit.html.twig', [
        'segmento' => $segmento,
        'restaurantes' => $restaurantesPaginados, // Pasar los restaurantes paginados a la plantilla
        'form' => $form->createView(),
    ]);
}

#[Route('/recalcular-precio/{id}', name: 'recalcular_precio', methods: ['GET', 'POST'])]
public function recalcularPrecio(int $id, MetricsCalculatorService $metricsCalculatorService, EntityManagerInterface $entityManager, RestauranteRepository $restauranteRepository, PaginatorInterface $paginatorInterface, Request $request): Response
{
    $segmento = $entityManager->getRepository(Segmento::class)->find($id);
    
    if (!$segmento) {
        throw $this->createNotFoundException('El segmento no existe');
    }

    // Obtener el término de búsqueda del nombre del restaurante
    $nombreABuscar = $request->query->get('nombreRestaurante');
    
    // Obtener todos los restaurantes paginados y filtrados
    $query = $restauranteRepository->findByNombreRestaurante($nombreABuscar);
    $restaurantesPaginados = $paginatorInterface->paginate(
        $query,
        $request->query->getInt('page', 1), // Número de página
        10 // Número de elementos por página
    );

    $precioMedio = $metricsCalculatorService->calcularPrecioMedio($segmento);
    $segmento->setAvgPrice($precioMedio);

    $entityManager->persist($segmento);
    $entityManager->flush();

    // Renderizar la plantilla de edición nuevamente con el segmento actualizado y el formulario
    $form = $this->createForm(SegmentoType::class, $segmento);

    return $this->render('segmento/edit.html.twig', [
        'segmento' => $segmento,
        'restaurantes' => $restaurantesPaginados, // Pasar los restaurantes paginados a la plantilla
        'form' => $form->createView(),
    ]);
}

    #[Route('/delete-relation/{segmentoId}/{restauranteId}', name: 'delete_relation_segmento_restaurante', methods: ['POST'])]
    public function eliminarRelacion(int $segmentoId, int $restauranteId, EntityManagerInterface $entityManager): Response
    {
        // Obtener el segmento y el restaurante desde la base de datos
        $segmento = $entityManager->getRepository(Segmento::class)->find($segmentoId);
        $restaurante = $entityManager->getRepository(Restaurante::class)->find($restauranteId);

        // Verificar si tanto el segmento como el restaurante existen
        if (!$segmento || !$restaurante) {
            throw $this->createNotFoundException('No se encontró el segmento o el restaurante');
        }

        // Eliminar la relación
        $segmento->removeRestaurante($restaurante);
        $entityManager->flush();

        // Redirigir a alguna página después de eliminar la relación
        return $this->redirectToRoute('app_segmento_edit', ['id' => $segmentoId]);
    }




    #[Route('/add-relation/{segmentoId}/{restauranteId}', name: 'add_relation_segmento_restaurante', methods: ['POST'])]
    public function agregarRelacion(int $segmentoId, int $restauranteId, EntityManagerInterface $entityManager): Response
    {
        // Obtener el segmento y el restaurante desde la base de datos
        $segmento = $entityManager->getRepository(Segmento::class)->find($segmentoId);
        $restaurante = $entityManager->getRepository(Restaurante::class)->find($restauranteId);

        // Verificar si tanto el segmento como el restaurante existen
        if (!$segmento || !$restaurante) {
            throw $this->createNotFoundException('No se encontró el segmento o el restaurante');
        }

        // Verificar si la relación ya existe
        if ($segmento->getRestaurantes()->contains($restaurante)) {
            // Redirigir con un mensaje de error o manejar según la lógica de tu aplicación
            return $this->redirectToRoute('app_segmento_edit', ['id' => $segmentoId]);
        }

        // Agregar la relación
        $segmento->addRestaurante($restaurante);
        $entityManager->flush();

        // Redirigir a alguna página después de agregar la relación
        return $this->redirectToRoute('app_segmento_edit', ['id' => $segmentoId]);
    }


}
