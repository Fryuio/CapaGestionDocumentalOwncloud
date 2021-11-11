<?php

namespace App\Controller;

use ServicioWebDav;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentosController extends AbstractController
{
    /**
     * @Route("/misDocumentos", name="misDocumentos")
     */
    public function crearCarpetas(): Response
    {
        
        $servicioWebDav = new ServicioWebDav();
        /* $servicioWebDav->crearCarpetas("owncloud", "owncloud", "pruebasWebDav");
        $servicioWebDav->crearArchivo("owncloud", "owncloud", "hola.txt", "Hola soy antonio"); */
        $lista = $servicioWebDav->listarDirectorio("owncloud", "owncloud","Photos");
        dump($lista); die;
        return $this->render('documentos/misDocumentos.html.twig', [
            'controller_name' => 'DocumentosController'
        ]);
    }

}
