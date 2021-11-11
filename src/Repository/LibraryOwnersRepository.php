<?php

namespace App\Repository;

use App\Entity\LibraryOwners;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LibraryOwners|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraryOwners|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraryOwners[]    findAll()
 * @method LibraryOwners[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryOwnersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryOwners::class);
    }
    /**
     * Query que devuelve los propietarios de librería de un usuario
     * 
     * @param idUsuario El id del usuario
     * 
     * @return propietario Todos los propietarios del usuario
     * 
     */
   public function getPropietarioLibrerias($idUsuario) {
        $consulta = $this->createQueryBuilder('lo')
            ->where('lo.users = :idUsuario')
            ->setParameters(array(
                    'idUsuario' => $idUsuario
                )
            )
            ->getQuery();
        $propietario = $consulta->getOneOrNullResult();

        return $propietario;
   }

   /**
     * Función que inserta una nuevo propietario de librería en la base de datos
     * 
     * @param usuarioPropietario El usuario que será nuevo propietario
     * 
     */
    public function crearNuevoPropietario($usuarioPropietario) {

        $propietario = new LibraryOwners();
        $em = $this->getEntityManager();
        $propietario->setUsers($usuarioPropietario);

        $em->persist($propietario);
        $em->flush();
    }

    /**
     * Query que devuelve el último propietario de librería de un usuario
     * 
     * @param idUsuario El id del usuario
     * 
     * @return propietario El último propietario creado
     * 
     */
   public function getUltimoPropietario($idUsuario) {
    $consulta = $this->createQueryBuilder('lo')
        ->select('MAX(lo.id)')
        ->where('lo.users = :idUsuario')
        ->setParameters(array(
                'idUsuario' => $idUsuario
            )
        )
        ->getQuery();
    $idPropietario = $consulta->getSingleResult();
    $propietario = $this->find($idPropietario[1]);
    return $propietario;
    }

    /**
     * Query que elimina a un propietario de librería de un usuario
     * 
     * @param idUsuario El id del usuario
     * 
     */
   public function borrarPropietario($idUsuario) {
        $em = $this->getEntityManager();
        $propietario = $this->getPropietarioLibrerias($idUsuario);
        $em->remove($propietario);
        $em->flush();
    }
}
