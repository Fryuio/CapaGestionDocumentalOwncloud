<?php

namespace App\Repository;

use App\Entity\DocumentVersions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentVersions|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentVersions|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentVersions[]    findAll()
 * @method DocumentVersions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentVersionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentVersions::class);
    }

    // /**
    //  * @return DocumentVersions[] Returns an array of DocumentVersions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentVersions
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
