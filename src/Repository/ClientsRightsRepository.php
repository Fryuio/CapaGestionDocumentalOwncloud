<?php

namespace App\Repository;

use App\Entity\ClientsRights;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientsRights|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientsRights|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientsRights[]    findAll()
 * @method ClientsRights[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRightsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientsRights::class);
    }

    public function crearPermiso($cliente, $objetoLibreria, $permiso){
        $em = $this->getEntityManager();
        $permisoCliente = new ClientsRights();
        $permisoCliente->setClients($cliente);
        $permisoCliente->setLibraryObject($objetoLibreria);
        $permisoCliente->setAvailableRights($permiso);
        $em->persist($permisoCliente);
        $em->flush();

    }

    public function eliminarPermiso($clientRights){
        $em = $this->getEntityManager();
        $em->remove($clientRights);
        $em->flush();

    }

    public function obtenerPermisosUsuario($usuario, $libraryObject) {
        $consulta = $this->createQueryBuilder('cr')
            ->join('cr.clients', 'c')
            ->join('c.users', 'u')
            ->join('cr.availableRights', 'ar')
            ->where('u.id = :idUsuario')
            ->andWhere('cr.libraryObject = :libraryObject')
            ->setParameters([
                'idUsuario' => $usuario->getId(),
                'libraryObject' => $libraryObject->getId()
            ])->getQuery();

        $resultado = $consulta->getResult();
        return $resultado;
    }
}
