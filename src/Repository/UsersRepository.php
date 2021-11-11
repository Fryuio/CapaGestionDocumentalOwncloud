<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }


    /**
     * Función que inserta un nuevo usuario en la base de datos
     * 
     * @param nombre El nombre de usuario
     * @param email El email del usuario
     * @param grupo El grupo al que pertenece el usuario
     * @param password La contraseña del usuario
     * 
     */
    public function creaUsuario($nombre, $email, $grupo, $password){
        $usuario = new Users();
        $em = $this->getEntityManager();
        $usuario->setNombre($nombre);
        $usuario->setEmail($email);
        $usuario->setGrupo($grupo);
        $usuario->setPassword($password);

        $em->persist($usuario);
        $em->flush();

    }

    /**
     * Función que encuentra uno o varios usuarios según su nombre
     * 
     * @param nombreUsuario El nombre de usuario
     * @return resultado El resultado de la búsqueda
     * 
     */
    public function busquedaUsuarios($nombreUsuario, $libraryObject){
        if($nombreUsuario=="null"){
            $res = $this->findAll();
        }else {
            $consulta = $this->createQueryBuilder('u')
                ->where('u.nombre LIKE :nombreUsuario')
                ->setParameters([
                    'nombreUsuario' => '%'. $nombreUsuario . '%' 
                ])->getQuery();

            $res = $consulta->getResult();
        }
        if($res!=null){
            $permisos = $this->getEntityManager()->getRepository('App:AvailableRights')->findAll();
            $resultado = "<table class='table table-striped'>
                            <thead>
                                <th>Usuario</th>
                                <th>Permisos</th>
                                <th>Acciones</th>
                                <th>Permisos Actuales</th>
                            </thead>
                            <tbody>
                            ";

            foreach($res as $r) {
                $permisosActuales = $this->getEntityManager()->getRepository('App:ClientsRights')->obtenerPermisosUsuario($r, $libraryObject);
                $resultado.="<tr>
                                <td>".$r->getNombre()."</td>
                                <td>
                                <select id=".$r->getId()." class='selectPermisos form-control'>
                                <option value='defecto' selected>Elige un permiso...</option>";
                                foreach($permisos as $permiso) {
                                   $resultado.="     
                                    <option value=".$permiso->getRight().">".$permiso->getRight()."</option>";
                                }
                                $resultado.="
                                </select>
                                </td>
                                <td>
                                <div class='btn-group' role='group' aria-label='Basic example'>
                                    <a href='#' id=".'agregarPermiso'.$r->getId()." type='button' class='botonAgregarPermiso btn btn-success'>Agregar</a>
                                    <a href='#' id=".'quitarPermiso'.$r->getId()." type='button' class='botonQuitarPermiso btn btn-danger'>Quitar</a>
                                </div>
                                </td>
                                <td><h4>";
                                if($permisosActuales!=null){
                                    foreach($permisosActuales as $permisoActual){
                                        $resultado.="<span class='badge badge-dark' style='margin-left: 5px;'>".$permisoActual->getAvailableRights()->getRight()."</span>";
                                    }
                                }else {
                                    $resultado.="<span class='badge badge-dark' style='margin-left: 5px;'>Sin permisos</span>";
                                }
                                $resultado.="</h4></td>
                            </tr>";
            }

            $resultado .="</tbody></table>";
        }else {
            $resultado = "Sin datos con ese filtro de búsqueda";
        }

        return $resultado;
    }

    public function busquedaUsuariosLibreria($nombreUsuario, $carpetas, $idLibreria, $duenoLibreria){
        $libreria = $this->getEntityManager()->getRepository('App:DocumentLibrary')->find($idLibreria);
        $libraryOwner = $libreria->getLibraryOwners();
        $res=null;
        if($nombreUsuario!="null"){
            $consulta = $this->createQueryBuilder('u')
                ->where('u.nombre LIKE :nombreUsuario')
                ->andWhere('u.nombre NOT LIKE :duenoLibreria')
                ->setParameters([
                    'nombreUsuario' => '%'. $nombreUsuario . '%',
                    'duenoLibreria' => '%'. $duenoLibreria . '%'
                ])->getQuery();
            $res = $consulta->getResult();
        }else {
            $consulta = $this->createQueryBuilder('u')
                ->where('u.nombre NOT LIKE :duenoLibreria')
                ->setParameters([
                    'duenoLibreria' => '%'. $duenoLibreria . '%'
                ])->getQuery();
            $res = $consulta->getResult();
        }
        if($res!=null){
            $resultado = "<table class='table table-striped'>
                            <thead>
                                <th>Usuario</th>
                                <th>Objeto de la librería</th>
                                <th>Permisos Actuales</th>
                            </thead>
                            <tbody>
                            ";
            foreach($carpetas as $carpeta){
                $archivos = $this->getEntityManager()->getRepository('App:Document')->findBy(array('libraryNode' => $carpeta->getId()));
                if($archivos!=null){
                    foreach($archivos as $archivo){
                        foreach($res as $r) {
                            $permisosActuales = $this->getEntityManager()->getRepository('App:ClientsRights')->obtenerPermisosUsuario($r, $archivo->getLibraryObject());
                            if($libraryOwner->getUsers()!= $r && $permisosActuales!=null){
                                $resultado.="<tr>
                                                <td>".$r->getNombre()."</td>
                                                <td>".$archivo->getName()."</td>
                                                <td><h4>";
                                                foreach($permisosActuales as $permisoActual){
                                                    $resultado.="<span class='badge badge-dark' style='margin-left: 5px;'>".$permisoActual->getAvailableRights()->getRight()."</span>";
                                                }
                                                $resultado.="</h4></td>
                                            </tr>";
                            }
                        }
                    }
                }
                foreach($res as $r) {
                    $permisosActuales = $this->getEntityManager()->getRepository('App:ClientsRights')->obtenerPermisosUsuario($r, $carpeta->getLibraryObject());
                        if($libraryOwner->getUsers()!= $r && $permisosActuales!=null){
                            $resultado.="<tr>
                                        <td>".$r->getNombre()."</td>
                                        <td>".$carpeta->getName()."</td>
                                        <td><h4>";
                                        foreach($permisosActuales as $permisoActual){
                                            $resultado.="<span class='badge badge-dark' style='margin-left: 5px;'>".$permisoActual->getAvailableRights()->getRight()."</span>";
                                        }
                                        $resultado.="</h4></td>
                                    </tr>";
                        }
                }          
            }

            $resultado .="</tbody></table>";
        }else {
            $resultado = "Sin datos con ese filtro de búsqueda";
        }

        return $resultado;
    }

    
}
