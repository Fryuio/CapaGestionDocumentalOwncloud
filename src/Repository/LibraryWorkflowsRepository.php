<?php

namespace App\Repository;

use App\Entity\LibraryWorkflows;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LibraryWorkflows|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraryWorkflows|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraryWorkflows[]    findAll()
 * @method LibraryWorkflows[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryWorkflowsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryWorkflows::class);
    }

    // /**
    //  * @return LibraryWorkflows[] Returns an array of LibraryWorkflows objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LibraryWorkflows
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
