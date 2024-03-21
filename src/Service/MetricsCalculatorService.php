<?php
namespace App\Service;

use App\Entity\Segmento;
use Doctrine\ORM\EntityManagerInterface;

class MetricsCalculatorService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calcularPopularidadMedia(Segmento $segmento): float
    {
        $popularityMedia = $this->entityManager->getRepository('App\Entity\Restaurante')
            ->createQueryBuilder('r')
            ->select('AVG(r.popularity_rate)')
            ->where(':segmento MEMBER OF r.segmentos')
            ->setParameter('segmento', $segmento)
            ->getQuery()
            ->getSingleScalarResult();
            
        return (float) $popularityMedia;
    }

    public function calcularPrecioMedio(Segmento $segmento): float
    {
        $precioMedio = $this->entityManager->getRepository('App\Entity\Restaurante')
            ->createQueryBuilder('r')
            ->select('AVG(r.last_avg_price)')
            ->where(':segmento MEMBER OF r.segmentos')
            ->setParameter('segmento', $segmento)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) $precioMedio;
    }

    public function calcularSatisfaccionMedia(Segmento $segmento): float
    {
        $satisfaccionMedia = $this->entityManager->getRepository('App\Entity\Restaurante')
            ->createQueryBuilder('r')
            ->select('AVG(r.satisfaction_rate)')
            ->where(':segmento MEMBER OF r.segmentos')
            ->andWhere('r.satisfaction_rate IS NOT NULL')
            ->setParameter('segmento', $segmento)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) $satisfaccionMedia;
    }
}
