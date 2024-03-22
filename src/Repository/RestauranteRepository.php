<?php

namespace App\Repository;

use App\Entity\Restaurante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurante>
 *
 * @method Restaurante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurante[]    findAll()
 * @method Restaurante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestauranteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurante::class);
    }

    public function paginationQuery()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
        ;
    }

        /**
     * Busca restaurantes por el nombre del restaurante.
     *
     * @param string|null $nombreRestaurante
     * @return Restaurante[]
     */
    public function findByNombreRestaurante(?string $nombreRestaurante): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name LIKE :nombre')
            ->setParameter('nombre', '%' . $nombreRestaurante . '%')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Restaurante[] Returns an array of Restaurante objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Restaurante
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
