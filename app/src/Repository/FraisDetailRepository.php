<?php

namespace App\Repository;

use App\Entity\FraisDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FraisDetail>
 *
 * @method FraisDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method FraisDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method FraisDetail[]    findAll()
 * @method FraisDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FraisDetail::class);
    }

//    /**
//     * @return FraisDetail[] Returns an array of FraisDetail objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FraisDetail
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
