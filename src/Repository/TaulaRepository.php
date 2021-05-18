<?php

namespace App\Repository;

use App\Entity\Taula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Taula|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taula|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taula[]    findAll()
 * @method Taula[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaulaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taula::class);
    }

    // /**
    //  * @return Taula[] Returns an array of Taula objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Taula
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
