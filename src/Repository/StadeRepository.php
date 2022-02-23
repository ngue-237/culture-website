<?php

namespace App\Repository;

use App\Entity\Stade;
use App\Entity\StadeSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stade|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stade|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stade[]    findAll()
 * @method Stade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StadeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stade::class);
    }


    // /**
    //  * @return Stade[] Returns an array of Stade objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stade
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
public function findEntitiesByString($str){
    return $this->getEntityManager()
        ->createQuery( 'SELECT s
                FROM App:Stade s
                WHERE s.nom LIKE :str')->setParameter('str', '%'.$str.'%')->getResult();
}

    public function findByPrix()
    {
        return $this->createQueryBuilder('stades')
            ->orderBy('stades.prixph','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
