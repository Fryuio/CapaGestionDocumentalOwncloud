<?php

namespace App\Repository;

use App\Entity\LibraryNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LibraryNode|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraryNode|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraryNode[]    findAll()
 * @method LibraryNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryNodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryNode::class);
    }

    public function crearCarpeta($nombreCarpeta, $libreria, $path, $objetoLibreria, $parent) {
        $carpeta = new LibraryNode();
        $em = $this->getEntityManager();
        $carpeta->setName($nombreCarpeta);
        $carpeta->setDocumentLibrary($libreria);
        $carpeta->setLibraryObject($objetoLibreria);
        $carpeta->setPath($path);
        $carpeta->setParent($parent);

        $em->persist($carpeta);
        $em->flush();
    }

    public function obtenerListadoDirectorios($libreria) {
        
        $consulta = $this->createQueryBuilder('ln')
            ->where('ln.documentLibrary = :libreria')
            ->andWhere('ln.path <> :path')
            ->orderBy('ln.path', 'ASC')
            ->setParameters([                 
                'libreria' => $libreria,
                'path' => '/'
                ]

            )->getQuery();

        $directorios = $consulta->getResult();

        return $directorios;

    }

    public function borrarCarpeta($directorio) {
        $em = $this -> getEntityManager();
        $subDirectorios = $this->findBy(array('parent' => $directorio->getId()));
        if($subDirectorios!=null){
            foreach($subDirectorios as $subDirectorio){
                $this->borrarCarpeta($subDirectorio);
            }
        }
        $em->remove($directorio);
        $em->flush();
    }

    public function crearRaiz($nombreCarpeta, $libreria, $path, $objetoLibreria, $parent){
        $raiz = new LibraryNode();
        $em = $this->getEntityManager();
        $raiz->setName($nombreCarpeta);
        $raiz->setDocumentLibrary($libreria);
        $raiz->setLibraryObject($objetoLibreria);
        $raiz->setPath($path);
        $raiz->setParent($parent);

        $em->persist($raiz);
        $em->flush();
    }

    public function obtenerTodosDirectorios($libreria, $parent) {
        $consulta = $this->createQueryBuilder('ln')
            ->where('ln.documentLibrary = :libreria')
            ->andWhere('ln.parent = :parent')
            ->orderBy('ln.path', 'ASC')
            ->setParameters([                 
                'libreria' => $libreria,
                'parent' => $parent
                ]

            )->getQuery();

        $directorios = $consulta->getResult();

        return $directorios;
    }


    
}