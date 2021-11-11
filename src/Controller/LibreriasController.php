<?php

namespace App\Controller;

use App\Entity\DocumentLibrary;
use App\Entity\LibraryNode;
use App\Form\LibraryNodeType;
use App\Form\DocumentLibraryType;
use Imagick;
use ImagickException;
use ServicioWebDav;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Spatie\PdfToImage\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class LibreriasController extends AbstractController {

    /**
     * @Route("/libreria/{idLibreria}", name="libreria")
     */
    public function libreria(Request $request, $idLibreria): Response {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $libreria = $em->getRepository('App:DocumentLibrary')->find($idLibreria);
        $directorios = $em->getRepository('App:LibraryNode')->obtenerListadoDirectorios($libreria);
        $parent = $em->getRepository('App:LibraryNode')->findBy(array('path' => "/"));
        $ficheros = $em->getRepository('App:Document')->obtenerFicherosLibreria($idLibreria);
        $metadatos = $em->getRepository('App:DocumentMetadata')->obtenerMetadatos($idLibreria);
        $storages = $em->getRepository('App:Storages')->findBy(array('documentLibrary' => $libreria->getId()));
        $arbol = [
            "id" => -1,
            "text" => "Directorio raíz (/)",
            "nodes" => []
        ];
        $arbol = $this->getArbol($libreria, $parent[0], $arbol);
        $arbol = json_encode($arbol);
        return $this->render('libreria/libreria.html.twig', [
            'controller_name' => 'LibreriasController',
            'libreria' => $idLibreria,
            'directorios' => $directorios,
            'arbol' => $arbol,
            'ficheros' => $ficheros,
            'metadatos' => $metadatos,
            'storages' => $storages
        ]); 
    }

    /**
     * @Route("/crearLibreria", name="crearLibreria")
     */
    public function crearLibreria(Request $request): Response {
        $libreria = new DocumentLibrary();
        $formulario = $this->createForm(DocumentLibraryType::class, $libreria);
        $formulario->handleRequest($request);
        $propietario = null;
        if($formulario->isSubmitted() && $formulario->isValid()){
            $em = $this->getDoctrine()->getManager();
            $libreria = $formulario->getData();
            $nombreLibreria = $libreria->getName();
            $usuario = $em->getRepository('App:Users')->find($request->getSession()->get('usuario')->getId());
            if($request->getSession()->get('propietario')==null){
                $propietario = $em->getRepository('App:LibraryOwners')->getPropietarioLibrerias($usuario->getId());
                if($propietario==null){
                    $em->getRepository('App:LibraryOwners')->crearNuevoPropietario($usuario);
                    $propietario = $em->getRepository('App:LibraryOwners')->getPropietarioLibrerias($usuario->getId());
                    $request->getSession()->set('propietario', $propietario);
                }else {
                    $request->getSession()->set('propietario', $propietario);
                }
            }else{
                $propietario = $em->getRepository('App:LibraryOwners')->find($request->getSession()->get('propietario')->getId());
            }
            $em->getRepository('App:DocumentLibrary')->crearNuevaLibreria($nombreLibreria,$propietario);
            $libreria = $em->getRepository('App:DocumentLibrary')->findBy(array('libraryOwners' => $propietario->getId()));
            $objetoLibreria = $em->getRepository('App:LibraryObject')->crearLibraryObject();
            $em->getRepository('App:LibraryNode')->crearRaiz("raiz", $libreria[0], "/", $objetoLibreria, null);
            $this->addFlash(
                'libreriaCreada',
                'La librería con nombre '.$nombreLibreria.', se ha creado con éxito'
            );
            return $this->redirectToRoute('principal');
        }

        return $this->render('crearLibreria/crearLibreria.html.twig', [
            'controller_name' => 'LibreriasController',
            'formulario' => $formulario->createView() 
        ]);
    }

    /**
     * @Route("/borrarLibreria/{idLibreria}", name="borrarLibreria")
     */
    public function borrarLibreria(Request $request, $idLibreria): Response {
            $em = $this->getDoctrine()->getManager();
            $nombreLibreria = $em->getRepository('App:DocumentLibrary')->find($idLibreria)->getName();
            $em->getRepository('App:DocumentLibrary')->borrarLibreria($idLibreria);
            $this->addFlash(
                'libreriaBorrada',
                'La librería con nombre '.$nombreLibreria.', ha sido eliminada'
            );
            $libreriasUsuario = $em->getRepository('App:DocumentLibrary')->obtenerLibreriasPropiedadUsuario($request->getSession()->get('usuario')->getId());
            if($libreriasUsuario==null){
                $request->getSession()->remove('propietario');
                $em->getRepository('App:LibraryOwners')->borrarPropietario($request->getSession()->get('usuario')->getId());
            }
            return $this->redirectToRoute('principal');
    }

    /**
     * @Route("/libreria/{idLibreria}/crearCarpeta", name="crearCarpetaLibreria")
     */
    public function crearCarpetaLibreria(Request $request, $idLibreria): Response {
        $em = $this->getDoctrine()->getManager();
        $libreria = $em->getRepository('App:DocumentLibrary')->find($idLibreria);
        $carpeta = new LibraryNode();
        $formulario = $this->createForm(LibraryNodeType::class, $carpeta);
        $formulario->handleRequest($request);
        if($formulario->isSubmitted() && $formulario->isValid()){
            $carpeta = $formulario->getData();
            $nombreCarpeta = $carpeta->getName();
            $objetoLibreria = $em->getRepository('App:LibraryObject')->crearLibraryObject();
            $path = "/".$nombreCarpeta;
            $parent = $em->getRepository('App:LibraryNode')->findBy(array('documentLibrary' => $idLibreria));
            $fechaCreacion = new \DateTime('now');
            $clave = 'fechaCreacion';
            $valor = strval($fechaCreacion->format('Y-m-d H:i:s'));
            $em->getRepository('App:LibraryNode')->crearCarpeta($nombreCarpeta, $libreria, $path, $objetoLibreria, $parent[0]);
            $nuevaCarpeta = $em->getRepository('App:LIbraryNode')->findBy(array('name' => $nombreCarpeta));
            $em->getRepository('App:NodeMetadata')->insertarMetadatos($clave, $valor, $nuevaCarpeta[0]);
            $this->addFlash(
                'carpetaCreada',
                'La carpeta con nombre '.$nombreCarpeta.', se ha creado correctamente'
            );
            return $this->redirectToRoute('libreria', array('idLibreria' => $idLibreria));
        }

        return $this->render('libreria/crearCarpeta/crearCarpeta.html.twig', [
            'controller_name' => 'LibreriasController',
            'formulario' => $formulario->createView(),
            'libreria' => $idLibreria
        ]);
    }

    /**
     * @Route("/libreria/eliminarCarpeta/{idDirectorio}", name="eliminarCarpetaLibreria")
     */
    public function eliminarCarpetaLibreria(Request $request, $idDirectorio): Response {
            $em = $this->getDoctrine()->getManager();
            $directorio = $em -> getRepository('App:LibraryNode')->find($idDirectorio);
            $idLibreria = $directorio->getDocumentLibrary()->getId();
            $documentos = $em->getRepository('App:Document')->findBy(array('libraryNode' => $directorio->getId()));
            if($documentos!=null){
                $em->getRepository('App:DocumentMetadata')->borrarMetadatos($documentos);
                $em->getRepository('App:Document')->borrarDocumentos($documentos);
            }
            $em->getRepository('App:NodeMetadata')->borrarMetadatos($idDirectorio);
            $em->getRepository('App:LibraryNode')->borrarCarpeta($directorio);
            $this->addFlash(
                'carpetaEliminada',
                'La carpeta con nombre '.$directorio->getName().', se ha eliminado correctamente'
            );
            return $this->redirectToRoute('libreria', array('idLibreria' => $idLibreria));

    }

    /**
     * @Route("/libreria/crearSubCarpeta/{idDirectorio}", name="crearSubCarpetaLibreria")
     */
    public function crearSubCarpeta(Request $request, $idDirectorio): Response {

        $em = $this->getDoctrine()->getManager();
        $carpeta = new LibraryNode();
        $directorio = $em -> getRepository('App:LibraryNode')->find($idDirectorio);
        $formulario = $this->createForm(LibraryNodeType::class, $carpeta);
        $formulario->handleRequest($request);
        if($formulario->isSubmitted() && $formulario->isValid()){
            $em = $this->getDoctrine()->getManager();
            $carpeta = $formulario->getData();
            $nombreCarpeta = $carpeta->getName();
            $libreria = $em->getRepository('App:DocumentLibrary')->find($directorio->getDocumentLibrary()->getId());
            $objetoLibreria = $em->getRepository('App:LibraryObject')->crearLibraryObject();
            $path = $directorio->getPath()."/".$nombreCarpeta;
            $em->getRepository('App:LibraryNode')->crearCarpeta($nombreCarpeta, $libreria, $path, $objetoLibreria, $directorio);
            $this->addFlash(
                'carpetaCreada',
                'La carpeta con nombre '.$nombreCarpeta.', se ha creado correctamente'
            );
            return $this->redirectToRoute('libreria', array('idLibreria' => $directorio->getDocumentLibrary()->getId()));
        }

        return $this->render('libreria/crearCarpeta/crearCarpeta.html.twig', [
            'controller_name' => 'LibreriasController',
            'formulario' => $formulario->createView(),
            'libreria' => $directorio->getDocumentLibrary()->getId()
        ]);

    }

    /**
     * @Route("/libreria/generarArchivo/{idDirectorio}", name="generarArchivo")
     */
    public function generarArchivo(Request $request, $idDirectorio): Response {

         $em = $this->getDoctrine()->getManager();
        $carpeta = $em->getRepository('App:LibraryNode')->find($idDirectorio);
        $storages = $em->getRepository('App:Storages')->findBy(array('documentLibrary' => $carpeta->getDocumentLibrary()->getId()));

        /* $formulario->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $documento = $formulario->getData();
            $nombreDocumento = $documento->getName();
            $objetoLibreria = $em->getRepository('App:LibraryObject')->crearLibraryObject();
            $path = $carpeta->getPath()."/".$nombreDocumento; //Agregar la extensión del fichero.
            $em->getRepository('App:Document')->crearArchivo($nombreDocumento, $carpeta, $path, $objetoLibreria);
            $fechaCreacion = new \DateTime('now');
            $clave = 'fechaCreacion';
            $valor = strval($fechaCreacion->format('Y-m-d H:i:s'));
            $documento = $em->getRepository('App:Document')->findBy(array('name' => $documento->getName()));
            $em->getRepository('App:DocumentMetadata')->insertarMetadatos($clave, $valor, $documento[0]);
            $this->addFlash(
                'documentoCreado',
                'El documento con nombre '.$nombreDocumento.', se ha creado correctamente'
            );
            return $this->redirectToRoute('libreria', array('idLibreria' => $carpeta->getDocumentLibrary()->getId()));  */
        return $this->render('libreria/crearArchivo/crearArchivo.html.twig', [
            'controller_name' => 'LibreriasController',
            'libreria' => $carpeta->getDocumentLibrary()->getId(),
            'storages' => $storages,
            'idDirectorio' => $idDirectorio
        ]);

    }

     /**
     * @Route("/libreria/eliminarArchivo/{idDocumento}", name="eliminarArchivo")
     */
    public function eliminarArchivo(Request $request, $idDocumento): Response {
        $em = $this->getDoctrine()->getManager();
        $documento = $em -> getRepository('App:Document')->find($idDocumento);
        $anteriorVersion = $documento->getDocument();
        if($anteriorVersion!=null){
            $em->getRepository('App:Document')->actualizarUltimaVersion($anteriorVersion->getId(), true);
        }
        $servicioWebDav = new ServicioWebDav();
        $storageItem = $em->getRepository('App:StorageItem')->findOneBy(array('document' => $documento->getId()));
        $servicioWebDav->eliminarFichero("owncloud", "owncloud", $storageItem->getPath());
        $em->getRepository('App:Document')->borrarArchivo($documento);
        $this->addFlash(
            'archivoEliminado',
            'El archivo con nombre '.$documento->getName().', se ha eliminado correctamente'
        );
        return $this->redirectToRoute('principal');

    }

    /**
     * @Route("/libreria/{idLibreria}/permisosCarpeta/{idDirectorio}", name="permisosCarpeta")
     */
    public function permisosCarpeta($idDirectorio, $idLibreria, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $metadatos = $em->getRepository('App:NodeMetadata')->findBy(array('libraryNode' => $idDirectorio));
        return $this->render('libreria/permisosCarpeta/permisosCarpeta.html.twig', [
            'controller_name' => 'LibreriasController',
            'metadatos' => $metadatos,
            'idDirectorio' => $idDirectorio,
            'idLibreria' => $idLibreria
        ]);

    }

    /**
     * @Route("/libreria/tablaPermisosUsuario/{idDirectorio}/{nombreUsuario}", name="tablaPermisosUsuarioCarpeta")
     */
    public function tablaPermisosUsuarioCarpeta($nombreUsuario, $idDirectorio) {
        $em = $this->getDoctrine()->getManager();
        $carpeta = $em->getRepository('App:LibraryNode')->find($idDirectorio);
        $usuarios = $em->getRepository('App:Users')->busquedaUsuarios($nombreUsuario, $carpeta->getLibraryObject());
        $response = new Response($usuarios);
        return $response;

    }

    /**
     * @Route("/libreria/agregarPermisoCarpeta/{idUsuario}/{idDirectorio}/{permisoSeleccionado}", name="agregarPermisoCarpeta")
     */
    public function agregarPermisoCarpeta($idUsuario, $idDirectorio, $permisoSeleccionado) {

        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository('App:Clients')->findOneBy(array('users' => $idUsuario));
        $carpeta = $em->getRepository('App:LibraryNode')->find($idDirectorio);
        $usuario = $em->getRepository('App:Users')->find($idUsuario);
        $permiso = $em->getRepository('App:AvailableRights')->findOneBy(array('right' => $permisoSeleccionado));
        if($cliente!=null){
            $permisoCliente = $em->getRepository('App:ClientsRights')->findOneBy(array('availableRights' => $permiso->getId(), 'libraryObject' => $carpeta->getLibraryObject()->getId(), 'clients' => $cliente->getId()));
            if($permisoCliente==null){
                $em->getRepository('App:ClientsRights')->crearPermiso($cliente, $carpeta->getLibraryObject(), $permiso);
                $this->addFlash(
                    'permisoOtorgadoExito',
                    '¡El permiso de '.$permiso->getRight().' ha sido otorgado al usuario!'
                ); 
            }else {
                $this->addFlash(
                    'permisoYaOtorgado',
                    '¡El permiso de '.$permiso->getRight().' ya lo tiene el usuario!'
                ); 
            }
        }else {
            $em->getRepository('App:Clients')->crearCliente($usuario);
            $em->getRepository('App:ClientsRights')->crearPermiso($cliente, $carpeta->getLibraryObject(), $permiso);
            $this->addFlash(
                'permisoOtorgadoExito',
                '¡El permiso de '.$permiso->getRight().' ha sido otorgado al usuario!'
            ); 
        }
        return $this->redirectToRoute('permisosCarpeta', array('idDirectorio' => $idDirectorio, 'idLibreria' => $carpeta->getDocumentLibrary()->getId()));

    }

    /**
     * @Route("/libreria/quitarPermisoCarpeta/{idUsuario}/{idDirectorio}/{permisoSeleccionado}", name="quitarPermisoCarpeta")
     */
    public function quitarPermisoCarpeta($idUsuario, $idDirectorio, $permisoSeleccionado) {

        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository('App:Clients')->findOneBy(array('users' => $idUsuario));
        $carpeta = $em->getRepository('App:LibraryNode')->find($idDirectorio);
        $permiso = $em->getRepository('App:AvailableRights')->findOneBy(array('right' => $permisoSeleccionado));
        if($cliente!=null){
            $clientRight = $em->getRepository('App:ClientsRights')->findOneBy(array('availableRights' => $permiso->getId(), 'libraryObject' => $carpeta->getLibraryObject()->getId(), 'clients' => $cliente->getId()));
            if($clientRight!=null){
                $em->getRepository('App:ClientsRights')->eliminarPermiso($clientRight);
                $this->addFlash(
                    'permisoEliminadoExito',
                    '¡El permiso de '.$permiso->getRight().' ha sido retirado del usuario!'
                ); 
            } else {
                $this->addFlash(
                    'permisoNoEliminado',
                    '¡El permiso de '.$permiso->getRight().' no puede ser retirado del usuario porque no lo tiene!'
                ); 
            }
        }
        return $this->redirectToRoute('permisosCarpeta', array('idDirectorio' => $idDirectorio, 'idLibreria' => $carpeta->getDocumentLibrary()->getId()));

    }

    /**
     * @Route("/libreria/{idLibreria}/permisosArchivo/{idDirectorio}/{idArchivo}", name="permisosArchivo")
     */
    public function permisosArchivo($idArchivo, $idLibreria, $idDirectorio, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $metadatos = $em->getRepository('App:DocumentMetadata')->findBy(array('document' => $idArchivo));
        return $this->render('libreria/permisosArchivo/permisosArchivo.html.twig', [
            'controller_name' => 'LibreriasController',
            'metadatos' => $metadatos,
            'idArchivo' => $idArchivo,
            'idLibreria' => $idLibreria,
            'idDirectorio' => $idDirectorio
        ]);

    }

    /**
     * @Route("/libreria/{idLibreria}/busquedaAvanzada", name="busquedaAvanzada")
     */
    public function busquedaAvanzada($idLibreria, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $libreria = $em->getRepository('App:DocumentLibrary')->find($idLibreria);
        return $this->render('libreria/busquedaAvanzada/busquedaAvanzada.html.twig', [
            'controller_name' => 'LibreriasController',
            'libreria' => $libreria,
        ]);

    }

    /**
     * @Route("/libreria/tablaPermisosUsuario/{idDirectorio}/{idArchivo}/{nombreUsuario}", name="tablaPermisosUsuarioArchivo")
     */
    public function tablaPermisosUsuarioArchivo($nombreUsuario, $idDirectorio, $idArchivo) {
        $em = $this->getDoctrine()->getManager();
        $archivo = $em->getRepository('App:Document')->find($idArchivo);
        $usuarios = $em->getRepository('App:Users')->busquedaUsuarios($nombreUsuario, $archivo->getLibraryObject());
        $response = new Response($usuarios);
        return $response;

    }

    /**
     * @Route("/libreria/busquedaAvanzada/tablaBusquedaAvanzada/{idLibreria}", name="tablaBusquedaAvanzada")
     */
    public function tablaBusquedaAvanzada(Request $request, $idLibreria) {
        $em = $this->getDoctrine()->getManager();
        $libreria = $em->getRepository('App:DocumentLibrary')->find($idLibreria);
        $documentos = $em->getRepository('App:Document')->busquedaInformacion($libreria, $request->get('filtrosBusqueda'));
        $response = new Response($documentos);
        return $response;

    }

    /**
     * @Route("/libreria/agregarPermisoArchivo/{idUsuario}/{idDirectorio}/{idArchivo}/{permisoSeleccionado}", name="agregarPermisoArchivo")
     */
    public function agregarPermisoArchivo($idUsuario, $idDirectorio, $permisoSeleccionado, $idArchivo) {

        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository('App:Clients')->findOneBy(array('users' => $idUsuario));
        $archivo = $em->getRepository('App:Document')->find($idArchivo);
        $usuario = $em->getRepository('App:Users')->find($idUsuario);
        $permiso = $em->getRepository('App:AvailableRights')->findOneBy(array('right' => $permisoSeleccionado));
        $permisoCliente = $em->getRepository('App:ClientsRights')->findOneBy(array('availableRights' => $permiso->getId(), 'libraryObject' => $archivo->getLibraryObject()->getId(), 'clients' => $cliente->getId()));
        if($cliente!=null){
            if($permisoCliente==null){
                $em->getRepository('App:ClientsRights')->crearPermiso($cliente, $archivo->getLibraryObject(), $permiso);
                $this->addFlash(
                    'permisoOtorgadoExito',
                    '¡El permiso de '.$permiso->getRight().' ha sido otorgado al usuario!'
                ); 
            }else {
                $this->addFlash(
                    'permisoYaOtorgado',
                    '¡El permiso de '.$permiso->getRight().' ya lo tiene el usuario!'
                ); 
            }
        }else {
            $em->getRepository('App:Clients')->crearCliente($usuario);
            $em->getRepository('App:ClientsRights')->crearPermiso($cliente, $archivo->getLibraryObject(), $permiso);
            $this->addFlash(
                'permisoOtorgadoExito',
                '¡El permiso de '.$permiso->getRight().' ha sido otorgado al usuario!'
            ); 
        }
        return $this->redirectToRoute('permisosArchivo', array('idDirectorio' => $idDirectorio, 'idLibreria' => $archivo->getLibraryNode()->getDocumentLibrary()->getId(), 'idArchivo' => $idArchivo));

    }

    /**
     * @Route("/libreria/quitarPermisoArchivo/{idUsuario}/{idDirectorio}/{idArchivo}/{permisoSeleccionado}", name="quitarPermisoArchivo")
     */
    public function quitarPermisoArchivo($idUsuario, $idDirectorio, $permisoSeleccionado, $idArchivo) {

        $em = $this->getDoctrine()->getManager();
        $cliente = $em->getRepository('App:Clients')->findOneBy(array('users' => $idUsuario));
        $permiso = $em->getRepository('App:AvailableRights')->findOneBy(array('right' => $permisoSeleccionado));
        $archivo = $em->getRepository('App:Document')->find($idArchivo);
        $clientRight = $em->getRepository('App:ClientsRights')->findOneBy(array('availableRights' => $permiso->getId(), 'libraryObject' => $archivo->getLibraryObject()->getId(), 'clients' => $cliente->getId()));
        if($cliente!=null){
            if($clientRight!=null){
                $em->getRepository('App:ClientsRights')->eliminarPermiso($clientRight);
                $this->addFlash(
                    'permisoEliminadoExito',
                    '¡El permiso de '.$permiso->getRight().' ha sido retirado del usuario!'
                ); 
            } else {
                $this->addFlash(
                    'permisoNoEliminado',
                    '¡El permiso de '.$permiso->getRight().' no puede ser retirado del usuario porque no lo tiene!'
                ); 
            }
        }
        return $this->redirectToRoute('permisosArchivo', array('idDirectorio' => $idDirectorio, 'idLibreria' => $archivo->getLibraryNode()->getDocumentLibrary()->getId(), 'idArchivo' => $idArchivo));

    }

    /**
     * @Route("/libreria/gestionPermisosLibreria/{idLibreria}", name="permisosObjetosLibreria")
     */
    public function gestionPermisosLibreria($idLibreria) {

        return $this->render('libreria/gestionPermisosLibreria/permisosLibreria.html.twig', [
            'controller_name' => 'LibreriasController',
            'idLibreria' => $idLibreria
        ]);


    }

    /**
     * @Route("/libreria/tablaPermisosUsuarioLibreria/{idLibreria}/{nombreUsuario}", name="tablaPermisosUsuarioLibreria")
     */
    public function tablaPermisosUsuarioLibreria($nombreUsuario, $idLibreria) {
        $em = $this->getDoctrine()->getManager();
        $libreria= $em->getRepository('App:DocumentLibrary')->find($idLibreria);
        $duenoLibreria = $libreria->getLibraryOwners()->getUsers()->getNombre();
        $carpetas = $em->getRepository('App:LibraryNode')->findBy(array('documentLibrary' => $idLibreria));
        $usuarios = $em->getRepository('App:Users')->busquedaUsuariosLibreria($nombreUsuario, $carpetas, $idLibreria, $duenoLibreria);
        $response = new Response($usuarios);
        return $response;

    }

    /**
     * @Route("/libreria/cargarArchivo/{idDirectorio}/{idLibreria}", name="cargarArchivo")
     */
    public function cargarArchivo(Request $request, $idDirectorio, $idLibreria) {
        $em = $this->getDoctrine()->getManager();
        $storages = $em->getRepository('App:Storages')->findBy(array('documentLibrary' => $idLibreria));
        if($request->files->get('fichero')!=null){
            $servicioWebDav = new ServicioWebDav();
            $fichero = $request->files->get('fichero');
            $storageSeleccionado = $request->get('storageSeleccionado');
            $metadatosUsuario = $request->get('metadatos');
            $metadatosUsuario = json_decode($metadatosUsuario);
            $fechaCreacion = new \DateTime();
            $metadatos = [
                "fechaCreacion" => $fechaCreacion->format("Y-m-d H:i:s"),
                "peso" => $fichero->getSize()
            ];
            $contenidoFichero = file_get_contents($fichero->getPathname());
            $carpeta = $em->getRepository('App:LibraryNode')->find($idDirectorio);
            $path = $carpeta->getPath().'/'.$fichero->getClientOriginalName();
            $objetoLibreria = $em->getRepository('App:LibraryObject')->crearLibraryObject();
            $archivoCreado = $em->getRepository('App:Document')->findOneBy(array('name'=> $fichero->getClientOriginalName()));
            if($archivoCreado!=null){
                $documento = $em->getRepository('App:Document')->findOneBy(array('path' => $path));
                $numeroFicheros = 1;
                $datosNuevoFichero = [];
                $primeraVersion = [];
                $primeraVersion= $this->encontrarPrimeraVersion($documento, $primeraVersion);
                $datosNuevoFichero = $this->actualizarVersionesFicheros($carpeta, $primeraVersion, $numeroFicheros, $datosNuevoFichero);
                $documentoVersionAntigua = $em->getRepository('App:Document')->find($datosNuevoFichero["idAntiguoFichero"]);
                $servicioWebDav->copiaFichero("owncloud", "owncloud", "/".$documentoVersionAntigua->getName(), "/versiones"."/".$documentoVersionAntigua->getName());
                $pathAntiguo = "/".$documentoVersionAntigua->getName();
                $nuevoPathStorage = "/versiones"."/".$documentoVersionAntigua->getName();
                $em->getRepository('App:StorageItem')->actualizarPath($pathAntiguo, $nuevoPathStorage);
                $servicioWebDav->eliminarFichero("owncloud", "owncloud", "/".$documentoVersionAntigua->getName());
                $em->getRepository('App:Document')->crearArchivo($datosNuevoFichero["nuevoNombreFichero"], $carpeta, $datosNuevoFichero["nuevoPathFichero"], $objetoLibreria, $documentoVersionAntigua, true);
                $nuevoDocumento = $em->getRepository('App:Document')->findOneBy(array('path' => $datosNuevoFichero["nuevoPathFichero"]));
                $servicioWebDav->crearArchivo("owncloud", "owncloud", $datosNuevoFichero["nuevoNombreFichero"], $contenidoFichero);
                $storage = $em->getRepository('App:Storages')->find($storageSeleccionado);
                $pathFicheroEnOwncloud = '/'.$datosNuevoFichero["nuevoNombreFichero"];
                $em->getRepository('App:StorageItem')->crearStorageItem($storage, $pathFicheroEnOwncloud, $nuevoDocumento);
                foreach($metadatosUsuario as $metadato){
                    $em->getRepository('App:DocumentMetadata')->insertarMetadatos($metadato->clave, $metadato->valor, $nuevoDocumento);
                }
                foreach($metadatos as $clave => $valor){
                    $em->getRepository('App:DocumentMetadata')->insertarMetadatos($clave, $valor, $nuevoDocumento);
                }
                $this->addFlash(
                    'archivoCargado',
                    '¡El archivo '.$fichero->getClientOriginalName().' ha sido cargado al sistema con éxito!'
                ); 
            }else {
                $servicioWebDav->crearArchivo("owncloud", "owncloud", $fichero->getClientOriginalName(), $contenidoFichero);
                $em->getRepository('App:Document')->crearArchivo($fichero->getClientOriginalName(), $carpeta, $path, $objetoLibreria);
                $documento = $em->getRepository('App:Document')->findOneBy(array('path' => $path));
                $storage = $em->getRepository('App:Storages')->find($storageSeleccionado);
                $pathFicheroEnOwncloud = '/'.$fichero->getClientOriginalName();
                $em->getRepository('App:StorageItem')->crearStorageItem($storage, $pathFicheroEnOwncloud, $documento);
                foreach($metadatosUsuario as $metadato){
                    $em->getRepository('App:DocumentMetadata')->insertarMetadatos($metadato->clave, $metadato->valor, $documento);
                }
                foreach($metadatos as $clave => $valor){
                    $em->getRepository('App:DocumentMetadata')->insertarMetadatos($clave, $valor, $documento);
                }
                $this->addFlash(
                    'archivoCargado',
                    '¡El archivo '.$fichero->getClientOriginalName().' ha sido cargado al sistema con éxito!'
                ); 
            }
            $response = new Response("Hecho");
            return $response; 
        }else {
            
            return $this->render('libreria/cargarArchivo/cargarArchivo.html.twig', [
                'controller_name' => 'LibreriasController',
                'libreria' => $idLibreria,
                'idDirectorio' => $idDirectorio,
                'storages' => $storages
            ]);

        }

    }

    /**
     * @Route("/libreria/descargarArchivo/{idDocumento}", name="descargarArchivo")
     */
    public function descargarArchivo(Request $request, $idDocumento) {
        $em = $this->getDoctrine()->getManager();
        $servicioWebDav = new ServicioWebDav();
        $storageItem = $em->getRepository('App:StorageItem')->findOneBy(array('document' => $idDocumento));
        $documento = $em->getRepository('App:Document')->find($idDocumento);
        $fichero = $servicioWebDav->leerArchivo("owncloud", "owncloud", $storageItem->getPath());
        $response = new StreamedResponse(function() use ($fichero) {
            fpassthru($fichero);
            exit();
        });
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$documento->getName());
        return $response;
        

    }

    /**
     * @Route("/libreria/versionado/{idLibreria}/{idDocumento}", name="versionadoArchivos")
     */
    public function versionadoArchivo(Request $request, $idLibreria, $idDocumento) {
        $em = $this->getDoctrine()->getManager();
        $documento = $em->getRepository('App:Document')->find($idDocumento);
        $primeraVersion = [];
        $primeraVersion = $this->encontrarPrimeraVersion($documento, $primeraVersion);
        $listaArchivos = [];
        $listaArchivos = $this->listaVersionado($documento->getLibraryNode(), $primeraVersion, $listaArchivos);
        array_unshift($listaArchivos, $primeraVersion);
        $metadatos = [];
        foreach($listaArchivos as $archivo){ 
            array_push($metadatos,$em->getRepository('App:DocumentMetadata')->findBy(array('document' => $archivo->getId())));
        }
        return $this->render('libreria/versionadoArchivos/versionadoArchivos.html.twig', [
            'controller_name' => 'LibreriasController',
            'idDocumento' => $idDocumento,
            'idLibreria' => $idLibreria,
            'listaVersiones' => $listaArchivos,
            'metadatos' => $metadatos,
            'primeraVersion' => $primeraVersion
        ]);
        

    }

    /**
     * @Route("/libreria/previsualizadorArchivos/{idDocumento}", name="previsualizadorArchivos")
     */
    public function previsualizadorArchivos(Request $request, $idDocumento) {
        $em = $this->getDoctrine()->getManager();
        $documento = $em->getRepository('App:Document')->find($idDocumento);
        $libreria = $documento->getLibraryNode()->getDocumentLibrary();
        $metadatos = $em->getRepository('App:DocumentMetadata')->findBy(array('document' => $idDocumento));
        $servicioWebDav = new ServicioWebDav();
        $storageItem = $em->getRepository('App:StorageItem')->findOneBy(array('document' => $idDocumento));
        $documento = $em->getRepository('App:Document')->find($idDocumento);
        $extensionDocumento = explode(".", $documento->getName());
        $fichero = $servicioWebDav->leerContenidosArchivo("owncloud", "owncloud", $storageItem->getPath());
        if($extensionDocumento[1]=="pdf"){
            /* Lógica imagick */
            $directorioArchivos = "/home/antonio/proyecto/web/var/tmp/";
            $gestor = fopen($directorioArchivos.$documento->getName(), "w");
            fwrite($gestor, $fichero);     
            fclose($gestor);
            $comando = "cd /home/antonio/proyecto/web/var/tmp; gs -sDEVICE=png16m -dTextAlphaBits=4 -r300 -o ".$extensionDocumento[0].".png ".$documento->getName();
            /* gs -sDEVICE=png16m -dTextAlphaBits=4 -r300 -o a.png a.pdf */
            $output = array();
            $ret = 0;
            exec($comando, $output, $ret);
            echo $ret; print_r($output); die;
            $file = "/home/antonio/proyecto/web/var/tmp/".$extensionDocumento[0].".png";
            $response = new BinaryFileResponse($file);
            return $this->render('libreria/previsualizadorArchivos/previsualizadorArchivos.html.twig', [
                'controller_name' => 'LibreriasController',
                'idDocumento' => $idDocumento,
                'idLibreria' => $libreria->getId(),
                'metadatos' => $metadatos,
                'textoFichero' => $fichero,
                'extensionDocumento' => $extensionDocumento[1],
                'respuesta' => $response
            ]);
            
        }else {
            return $this->render('libreria/previsualizadorArchivos/previsualizadorArchivos.html.twig', [
                'controller_name' => 'LibreriasController',
                'idDocumento' => $idDocumento,
                'idLibreria' => $libreria->getId(),
                'metadatos' => $metadatos,
                'textoFichero' => $fichero,
                'extensionDocumento' => $extensionDocumento[1]
            ]);
        }
        

    }

    public function getArbol($libreria, $parent, &$arbol){
        $em = $this->getDoctrine()->getManager();
        $directorios = $em->getRepository('App:LibraryNode')->obtenerTodosDirectorios($libreria, $parent);
        foreach($directorios as $directorio) {
            $nodo = [
                "text" => $directorio->getPath(),
                "id" => $directorio->getId(),
                "nodes" => [],
                "icon" => " glyphicon glyphicon-folder-open "
            ];
                $this->getArbol($libreria,$directorio,$nodo);
                $arbol["nodes"][] = $nodo;
        }
        return $arbol;
        
        
    }

    public function actualizarVersionesFicheros($carpeta, $parent, &$numeroFicheros, &$datosNuevoFichero){
        $em = $this->getDoctrine()->getManager();
        $documentos = $em->getRepository('App:Document')->obtenerDocumentos($carpeta, $parent);
        if($documentos!=null){
            foreach($documentos as $d) {
                $numeroFicheros++;
                if($d->getLastVersion()==true){
                    $idVersionAntigua = $d->getId();
                    $nuevoNombreFichero = explode("(".($numeroFicheros-1).").", $d->getName());
                    $nuevoNombreFichero = $nuevoNombreFichero[0]."(".$numeroFicheros.")".".".$nuevoNombreFichero[1];
                    $nuevoPath = $d->getPath();
                    $nuevoPath = str_replace($d->getName(), $nuevoNombreFichero, $nuevoPath);
                    $em->getRepository('App:Document')->actualizarUltimaVersion($idVersionAntigua, false);
                    $datosNuevoFichero = array('idAntiguoFichero' => $idVersionAntigua, 'nuevoNombreFichero' => $nuevoNombreFichero, 'nuevoPathFichero' => $nuevoPath);
                }else {
                    $this->actualizarVersionesFicheros($carpeta, $d, $numeroFicheros, $datosNuevoFichero);
                }
            }
        }else {
            $idVersionAntigua = $parent->getId();
            $nuevoNombreFichero = explode(".", $parent->getName());
            $nuevoNombreFichero = $nuevoNombreFichero[0]."(".$numeroFicheros.")".".".$nuevoNombreFichero[1];
            $nuevoPath = $parent->getPath();
            $nuevoPath = str_replace($parent->getName(), $nuevoNombreFichero, $nuevoPath);
            $em->getRepository('App:Document')->actualizarUltimaVersion($idVersionAntigua, false);
            $datosNuevoFichero = array('idAntiguoFichero' => $idVersionAntigua, 'nuevoNombreFichero' => $nuevoNombreFichero, 'nuevoPathFichero' => $nuevoPath);
        }
        return $datosNuevoFichero; 
    }

    public function encontrarPrimeraVersion($documento, &$primeraVersion) {
        if($documento->getDocument()!=null){
            $this->encontrarPrimeraVersion($documento->getDocument(), $primeraVersion);
        }else {
            $primeraVersion = $documento;
        }
        return $primeraVersion;
        
    }

    public function listaVersionado($carpeta, $parent, &$listaArchivos) {
        $em = $this->getDoctrine()->getManager();
        $documentos = $em->getRepository('App:Document')->obtenerDocumentos($carpeta, $parent);
        foreach($documentos as $documento) {
                array_push($listaArchivos, $documento);
                $this->listaVersionado($carpeta,$documento,$listaArchivos);
        }
        return $listaArchivos;
    }

    

    

    

    




    


}
