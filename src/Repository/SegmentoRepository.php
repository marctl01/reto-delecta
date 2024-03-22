<?php

namespace App\Repository;

use App\Entity\Segmento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Segmento>
 *
 * @method Segmento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Segmento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Segmento[]    findAll()
 * @method Segmento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SegmentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Segmento::class);
    }


    public function paginationQuery()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
        ;
    }

    /**
     * Busca segmentos por el nombre.
     *
     * @param string|null $nombreSegmento
     * @return Restaurante[]
     */
    public function findByNombreSegmento(?string $nombreSegmento): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name LIKE :nombre')
            ->setParameter('nombre', '%' . $nombreSegmento . '%')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

    //    public function findOneBySomeField($value): ?Segmento
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}