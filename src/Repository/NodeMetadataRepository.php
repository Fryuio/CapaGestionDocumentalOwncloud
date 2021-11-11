<?php

namespace App\Repository;

use App\Entity\NodeMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NodeMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method NodeMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method NodeMetadata[]    findAll()
 * @method NodeMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NodeMetadataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeMetadata::class);
    }

    public function insertarMetadatos($clave, $valor, $carpeta){
        $em = $this->getEntityManager();
        $metadato = new NodeMetadata();
        $metadato->setClave($clave);
        $metadato->setValor($valor);
        $metadato->setLibraryNode($carpeta);
        $em->persist($metadato);
        $em->flush();

    }

    public function borrarMetadatos($idDirectorio) {
        $em = $this->getEntityManager();
        $metadatos = $this->findBy(array('libraryNode' => $idDirectorio));
        foreach($metadatos as $metadato){
            $em->remove($metadato);
            $em->flush();
        }
    }
}
