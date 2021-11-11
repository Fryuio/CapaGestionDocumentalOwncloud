<?php

namespace App\Repository;

use App\Entity\DocumentMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentMetadata[]    findAll()
 * @method DocumentMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentMetadataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentMetadata::class);
    }

    public function insertarMetadatos($clave, $valor, $documento){
        $em = $this->getEntityManager();
        $metadatosDocumento = new DocumentMetadata();
        $metadatosDocumento->setClave($clave);
        $metadatosDocumento->setValor($valor);
        $metadatosDocumento->setDocument($documento);
        $em->persist($metadatosDocumento);
        $em->flush();
    }

    public function obtenerMetadatos($idLibreria){
        $consulta = $this->createQueryBuilder('dm')
        ->join('dm.document', 'd')
        ->join('d.libraryNode', 'ln')
        ->join('ln.documentLibrary', 'dl')
        ->where('dl.id = :id' )
        ->setParameters(array(
            'id' => $idLibreria
            )
        )->getQuery();
    $metadatos = $consulta->getResult();
    return $metadatos;

    }

    public function borrarMetadatos($documentos){
        $em = $this->getEntityManager();
        foreach($documentos as $documento) {
            $metadato = $this->findOneBy(array('document'=> $documento));
            $em->remove($metadato);
            $em->flush();
        }
    }
}
