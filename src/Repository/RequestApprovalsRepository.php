<?php

namespace App\Repository;

use App\Entity\RequestApprovals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestApprovals|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestApprovals|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestApprovals[]    findAll()
 * @method RequestApprovals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestApprovalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestApprovals::class);
    }

    // /**
    //  * @return RequestApprovals[] Returns an array of RequestApprovals objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequestApprovals
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
