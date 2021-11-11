<?php

namespace App\Repository;

use App\Entity\WorkflowsTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkflowsTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkflowsTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkflowsTypes[]    findAll()
 * @method WorkflowsTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkflowsTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowsTypes::class);
    }

    // /**
    //  * @return WorkflowsTypes[] Returns an array of WorkflowsTypes objects
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
    public function findOneBySomeField($value): ?WorkflowsTypes
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
