<?php

namespace App\Repository;

use App\Entity\Storages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Storages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Storages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Storages[]    findAll()
 * @method Storages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoragesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Storages::class);
    }

    public function crearStorage($storageType, $metadatos, $path, $libreria){
        $em = $this->getEntityManager();
        $storage = new Storages();
        $storage->setStorageTypes($storageType);
        $storage->setMetadata($metadatos);
        $storage->setStoragePath($path);
        $storage->setDocumentLibrary($libreria);
        $em->persist($storage);
        $em->flush();
    }

}
