<?php

namespace App\Repository;

use App\Entity\Cmnt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cmnt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cmnt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cmnt[]    findAll()
 * @method Cmnt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cmnt::class);
    }

    // /**
    //  * @return Cmnt[] Returns an array of Cmnt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cmnt
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
