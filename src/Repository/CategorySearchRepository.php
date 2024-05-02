<?php

namespace App\Repository;

use App\Entity\CategorySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorySearch>
 *
 * @method CategorySearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorySearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorySearch[]    findAll()
 * @method CategorySearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorySearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorySearch::class);
    }

    //    /**
    //     * @return CategorySearch[] Returns an array of CategorySearch objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CategorySearch
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
