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

    //    /**
    //     * @return Segmento[] Returns an array of Segmento objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

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
