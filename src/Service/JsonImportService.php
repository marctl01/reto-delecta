<?php

namespace App\Service;

use App\Entity\Segmento;
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
    
        // Iterar sobre cada objeto en el JSON
        foreach ($data as $segmentoData) {
            // Crear una instancia de Segmento utilizando el SegmentoFactory
            $segmentoFactory = new SegmentoFactory($this->validator);
            $segmento = $segmentoFactory->createSegmento($segmentoData);
    
            // Iterar sobre los restaurantes de este segmento
            foreach ($segmentoData['restaruants'] as $restauranteData) {
                // Crear una instancia de Restaurante utilizando el RestauranteFactory
                $restauranteFactory = new RestauranteFactory($this->validator);
                $restaurante = $restauranteFactory->createRestaurante($restauranteData);
    
                // Establecer la relación entre Restaurante y Segmento
                $restaurante->addSegmento($segmento);
                $segmento->addRestaurante($restaurante);
    
                // Persistir el restaurante si es necesario
                $this->entityManager->persist($restaurante);
            }
    
            // Persistir el segmento
            $this->entityManager->persist($segmento);
        }
    
        // Flush para persistir los cambios en la base de datos
        $this->entityManager->flush();

        
    }
}
