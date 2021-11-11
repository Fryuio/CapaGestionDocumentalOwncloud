<?php

namespace App\Repository;

use App\Entity\ObjectRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ObjectRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectRequest[]    findAll()
 * @method ObjectRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjectRequest::class);
    }

    // /**
    //  * @return ObjectRequest[] Returns an array of ObjectRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ObjectRequest
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
