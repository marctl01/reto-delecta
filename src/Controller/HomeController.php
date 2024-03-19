<?php

namespace App\Controller;

use App\Service\JsonImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/import', name: 'import_data', methods: ['POST'])]
    public function import(Request $request, JsonImportService $jsonImportService): Response
    {
        // Obtener el archivo JSON del formulario
        $jsonFile = $request->files->get('jsonFile');

        // Validar si se proporcionó un archivo
        if (!$jsonFile) {
            throw new \InvalidArgumentException('No se proporcionó un archivo JSON.');
        }

        // Leer el contenido del archivo JSON
        $jsonData = file_get_contents($jsonFile->getPathname());

        // Procesar el archivo JSON utilizando el servicio JsonImportService
        $jsonImportService->importDataFromJson($jsonData);

        // Redirigir a alguna otra página o mostrar un mensaje de éxito
        return $this->redirectToRoute('app_home');
    }
}
