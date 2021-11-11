<?php

namespace App\Repository;

use App\Entity\LibraryObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LibraryObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraryObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraryObject[]    findAll()
 * @method LibraryObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryObject::class);
    }

    public function crearLibraryObject() {

        $libraryObject = new LibraryObject();

        $em = $this->getEntityManager();

        $em->persist($libraryObject);
        $em->flush();

        return $libraryObject;
    }


}
