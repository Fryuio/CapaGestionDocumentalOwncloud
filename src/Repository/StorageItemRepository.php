<?php

namespace App\Repository;

use App\Entity\StorageItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StorageItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method StorageItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method StorageItem[]    findAll()
 * @method StorageItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorageItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StorageItem::class);
    }

    public function crearStorageItem($storage, $path, $documento) {
        $em = $this->getEntityManager();
        $storageItem = new StorageItem();

        $storageItem->setStorages($storage);
        $storageItem->setPath($path);
        $storageItem->setDocument($documento);

        $em->persist($storageItem);
        $em->flush();
    }

    public function actualizarPath($pathAntiguo, $pathNuevo) {
        $consulta = $this->createQueryBuilder('si')
            ->update()
            ->set('si.path', ':nuevoPath')
            ->where('si.path = :antiguoPath')
            ->setParameters([
                'nuevoPath' => $pathNuevo,
                'antiguoPath' => $pathAntiguo
            ])->getQuery()->execute();
    }
}
