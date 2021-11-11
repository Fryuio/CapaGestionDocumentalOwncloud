<?php

namespace App\Repository;

use App\Entity\DocumentLibrary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentLibrary|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentLibrary|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentLibrary[]    findAll()
 * @method DocumentLibrary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentLibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentLibrary::class);
    }
    /**
     * Función que inserta una nueva librería en la base de datos
     * 
     * @param nombreLibreria El nombre de la libreria
     * @param propietario El propietario de la librería
     * 
     */
    public function crearNuevaLibreria($nombreLibreria,$propietario) {

        $libreria = new DocumentLibrary();
        $em = $this->getEntityManager();
        $libreria->setName($nombreLibreria);
        $libreria->setLibraryOwners($propietario);

        $em->persist($libreria);
        $em->flush();

    }
    /**
     * Query que obtiene las librerías en propiedad de un usuario
     * 
     * @param idUsuario El identificador unívoco del usuario
     * 
     * @return librerias Las librerias propiedad del usuario
     * 
     */
    public function obtenerLibreriasPropiedadUsuario($idUsuario) {
        $consulta = $this->createQueryBuilder('dl')
            ->select('dl.id, dl.name')
            ->join('dl.libraryOwners', 'lo')
            ->join('lo.users', 'u')
            ->where('u.id = :id' )
            ->setParameters(array(
                'id' => $idUsuario
                )
            )->getQuery();
        $librerias = $consulta->getResult();

        return $librerias;

    }
    /**
     * Función que elimina la librería en propiedad de un usuario
     * 
     * @param idUsuario El identificador unívoco de la librería
     * 
     */
    public function borrarLibreria($idLibreria) {
        $em = $this->getEntityManager();
        $libreria = $this->find($idLibreria);
        $em->remove($libreria);
        $em->flush();
    }
}
