<?php

namespace App\Repository;

use App\Entity\StorageTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StorageTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method StorageTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method StorageTypes[]    findAll()
 * @method StorageTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorageTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StorageTypes::class);
    }

    // /**
    //  * @return StorageTypes[] Returns an array of StorageTypes objects
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
    public function findOneBySomeField($value): ?StorageTypes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
