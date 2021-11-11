<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }


    /**
     * Función que inserta un nuevo documento en la base de datos
     * 
     * @param nombreDocumento El nombre del documento
     * @param carpeta La carpeta dónde se alojará el documento
     * @param path La ruta del archivo
     * @param objetoLibreria El objeto libreria asociado al documento
     * 
     */
    public function crearArchivo($nombreDocumento, $carpeta, $path, $objetoLibreria, $document = NULL, $lastVersion = 1){
        $documento = new Document();
        $em = $this->getEntityManager();
        $documento->setName($nombreDocumento);
        $documento->setLibraryObject($objetoLibreria);
        $documento->setPath($path);
        $documento->setLibraryNode($carpeta);
        $documento->setLastVersion($lastVersion);
        $documento->setDocument($document);

        $em->persist($documento);
        $em->flush();


    }
    /**
     * Consulta que obtiene todos los documentos contenidos en una libreria concreta
     * 
     * @param idLibreria EL identificador unívoco de la librería
     * 
     * @return documentos Los documentos contenidos en la librería
     * 
     */
    public function obtenerFicherosLibreria($idLibreria){

        $consulta = $this->createQueryBuilder('d')
            ->join('d.libraryNode', 'ln')
            ->join('ln.documentLibrary', 'dl')
            ->where('dl.id = :id' )
            ->setParameters(array(
                'id' => $idLibreria
                )
            )->getQuery();
        $documentos = $consulta->getResult();
        return $documentos;
    }

    public function borrarArchivo($documento) {
        $em = $this->getEntityManager();
        $em->remove($documento);
        $em->flush();
    }

    public function borrarDocumentos($documentos){
        $em = $this->getEntityManager();
        foreach($documentos as $documento){
            $em->remove($documento);
            $em->flush();
        }
    }

    public function obtenerDocumentos($carpeta, $parent) {
        $consulta = $this->createQueryBuilder('d')
            ->where('d.libraryNode = :carpeta')
            ->andWhere('d.document = :parent')
            ->orderBy('d.path', 'ASC')
            ->setParameters([     
                'carpeta' => $carpeta,            
                'parent' => $parent
                ]

            )->getQuery();
        $directorios = $consulta->getResult();

        return $directorios;
    }

    public function actualizarUltimaVersion($idArchivo, $lastVersion) {
        $consulta = $this->createQueryBuilder('d')
            ->update()
            ->set('d.lastVersion', ':lastVersion')
            ->where('d.id = :idArchivo')
            ->setParameters([
                'lastVersion' => $lastVersion,
                'idArchivo' => $idArchivo
            ])->getQuery()->execute();
    }

    public function busquedaInformacion($libreria, $arrayDatos){
        $res=null;
        if($arrayDatos!=null){
            $em = $this->getEntityManager();
                $consulta = $this->createQueryBuilder('d')
                    ->join('d.libraryNode', 'ln')
                    ->join('ln.documentLibrary', 'dl')
                    ->join('dl.libraryOwners', 'lo')
                    ->join('lo.users', 'u')
                    ->where('ln.documentLibrary = :libreria')
                    ->andWhere('d.name LIKE :nombreArchivo')
                    ->andWhere('u.nombre LIKE :nombreUsuario')
                    ->andWhere('ln.name LIKE :nombreCarpeta')
                    ->setParameters([
                        'nombreArchivo' => '%'. $arrayDatos['nombreArchivo']. '%',
                        'libreria' => $libreria,
                        'nombreUsuario' => '%'. $arrayDatos['nombreAutor']. '%',
                        'nombreCarpeta' => '%'. $arrayDatos['nombreCarpeta']. '%'
                        /* 'metadatosArchivo' => '%'. $arrayDatos['metadatosArchivo']. '%'
                        'metadatosCarpeta' => '%'. $arrayDatos['metadatosCarpeta']. '%', */
                    ])->getQuery();
                $res = $consulta->getResult();
            if($res!=null){
                $resultado = "<table class='table table-striped'>
                                <thead>
                                    <th>Nombre del documento</th>
                                    <th>Path</th>
                                    <th>Carpeta Contenedora</th>
                                </thead>
                                <tbody>
                                ";
                        foreach($res as $r) {
                            $resultado .= "<tr>";
                                $resultado.="<td>".$r->getName()."</td>";
                                $resultado.= "<td>".$r->getPath()."</td>";
                                $resultado.= "<td>".$r->getLibraryNode()->getName()."</td>";
                            $resultado.= "</tr>";
                        }
                $resultado .="</tbody></table>";
            }else {
                $resultado = "Sin datos con ese filtro de búsqueda";
            }
        } else {
            $resultado = "Sin datos con ese filtro de búsqueda";
        }

        return $resultado;
    }
}
