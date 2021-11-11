<?php

namespace App\Repository;

use App\Entity\WorkflowExecutions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkflowExecutions|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkflowExecutions|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkflowExecutions[]    findAll()
 * @method WorkflowExecutions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkflowExecutionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowExecutions::class);
    }

    // /**
    //  * @return WorkflowExecutions[] Returns an array of WorkflowExecutions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkflowExecutions
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
