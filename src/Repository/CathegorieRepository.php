<?php

namespace App\Repository;

use App\Entity\Cathegorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cathegorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cathegorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cathegorie[]    findAll()
 * @method Cathegorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CathegorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cathegorie::class);
    }

    // /**
    //  * @return Cathegorie[] Returns an array of Cathegorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cathegorie
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
