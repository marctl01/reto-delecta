<?php
namespace App\Service;

use App\Entity\Restaurante;
use App\Repository\SegmentoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Factory\SegmentoFactory;
use App\Factory\RestauranteFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonImportService
{
    private $entityManager;
    private $segmentoRepository;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, SegmentoRepository $segmentoRepository, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->segmentoRepository = $segmentoRepository;
        $this->validator = $validator;
    }

    public function importDataFromJson($jsonData)
{
    // Decodificar el JSON
    $data = json_decode($jsonData, true);
    
    // Array para almacenar restaurantes procesados
    $processedRestaurantes = [];

    // Iterar sobre cada objeto en el JSON
    foreach ($data as $segmentoData) {
        // Verificar si el segmento ya existe en la base de datos
        $existingSegmento = $this->segmentoRepository->findOneBy(['uidentifier' => $segmentoData['uidentifier']]);
        
        // Si el segmento no existe, crear uno nuevo
        if (!$existingSegmento) {
            $segmentoFactory = new SegmentoFactory($this->validator);
            $existingSegmento = $segmentoFactory->createSegmento($segmentoData);
        }
        
        // Iterar sobre los restaurantes de este segmento
        foreach ($segmentoData['restaurants'] as $restauranteData) {
            // Verificar si el restaurante ya ha sido procesado
            if (!isset($processedRestaurantes[$restauranteData['uidentifier']])) {
                // Verificar si el restaurante ya existe en la base de datos
                $existingRestaurante = $this->entityManager->getRepository(Restaurante::class)
                    ->findOneBy(['uidentifier' => $restauranteData['uidentifier']]);
                
                // Si el restaurante no existe, crear uno nuevo
                if (!$existingRestaurante) {
                    $restauranteFactory = new RestauranteFactory($this->validator);
                    $existingRestaurante = $restauranteFactory->createRestaurante($restauranteData);
                }
                
                // Marcar el restaurante como procesado
                $processedRestaurantes[$restauranteData['uidentifier']] = true;

                // Verificar si el restaurante ya está asociado al segmento
                if (!$existingSegmento->getRestaurantes()->contains($existingRestaurante)) {
                    $existingRestaurante->addSegmento($existingSegmento);
                    $existingSegmento->addRestaurante($existingRestaurante);
                }
            }
        }
        
        // Guardar el segmento
        $this->entityManager->persist($existingSegmento);
    }
    
    // Flush para guardar los cambios en la base de datos
    $this->entityManager->flush();
}

    
    
}
