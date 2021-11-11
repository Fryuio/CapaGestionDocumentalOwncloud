<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\LoginType;
use ServicioCURL;
use ServicioWebDav;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class StorageController extends AbstractController {

    /**
     * @Route("/nuevoStorage/{idLibreria}", name="nuevoStorage")
     */
    public function nuevoStorage($idLibreria): Response {
        return $this->render('storages/nuevoStorage.html.twig', [
            'controller_name' => 'StorageController',
            'idLibreria' => $idLibreria
        ]);
    }

    /**
     * @Route("/crearStorage/{tipoStorage}/{servidorStorage}/{usuario}/{password}/{idLibreria}/{nombreStorage}", name="crearStorage")
     */
    public function crearStorage($tipoStorage, $servidorStorage, $usuario, $password, $idLibreria, $nombreStorage): Response {
        $em = $this->getDoctrine()->getManager();
        $storageType = $em->getRepository('App:StorageTypes')->findOneBy(array('name' => $tipoStorage));
        $libreria = $em->getRepository('App:DocumentLibrary')->find($idLibreria);
        $metadatos = [
            'servidor' => $servidorStorage,
            'usuario' => $usuario,
            'password' => $password,
            'nombreStorage' => $nombreStorage
        ];
        $metadatos = json_encode($metadatos);
        $path = 'http://localhost/owncloud/remote.php/dav/files/'.$usuario.'/';
        $em->getRepository('App:Storages')->crearStorage($storageType, $metadatos, $path, $libreria);
        $this->addFlash(
            'storageCreado',
            '¡El storage de tipo '.$tipoStorage.' ha sido creado con éxito!'
        ); 
        $response = new Response("Hecho");
        return $response;
    }

}