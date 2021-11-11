<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\LoginType;
use ServicioCURL;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends AbstractController {
    /**
     * @Route("/home", name="home")
     */
    public function home(Request $request): Response {
        $session = $request->getSession();
        $servicioCURL = new ServicioCURL();
        $informacionAlmacenamientoUsuario = $servicioCURL->getInformacionCapacidadUsuario($session->get("nombreUsuario"));
        return $this->render('home/home.html.twig', [
            'controller_name' => 'InicioController',
            'total' => $informacionAlmacenamientoUsuario["totalGB"],
            'usado' => $informacionAlmacenamientoUsuario["usadoGB"] 
        ]);
    }

    /**
     * @Route("/principal", name="principal")
     */
    public function principal(Request $request): Response {
        $session = $request->getSession();
        $servicioCURL = new ServicioCURL();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('App:Users')->find($session->get("usuario")->getId());
        $libreriasUsuario = $em->getRepository('App:DocumentLibrary')->obtenerLibreriasPropiedadUsuario($session->get("usuario")->getId());
        return $this->render('principal/principal.html.twig', [
            'controller_name' => 'InicioController',
            'usuario' => $usuario,
            'libreriasUsuario' => $libreriasUsuario
        ]);
    }

     /**
     * @Route("/login", name="login")
     */
    public function login(Request $request): Response
    {
        $usuario = new Users();
        $formulario = $this->createForm(LoginType::class, $usuario);
        $formulario->handleRequest($request);
        if($formulario->isSubmitted() && $formulario->isValid()){
            $em = $this->getDoctrine()->getManager();
            $usuario = $formulario->getData();
            $nombreUsuario = $usuario->getNombre();
            $password = $usuario->getPassword();
            $usuariosValidos = $em->getRepository(Users::class)->findAll();
            $encontrado = false;
            $error = null;
            foreach($usuariosValidos as $u){
                if($u->getNombre()==$nombreUsuario){
                    if($u->getPassword()==$password){
                        $encontrado = true;
                        $nombreUsuario = $u->getNombre();
                        $fechaUltimoAcceso = $u->getFechaUltimoAcceso();
                        $usuario = $u;
                        break;
                    } else {
                        $error = 'password';
                    }
                }
            }

            if(!$encontrado){
                $error==null ? $error = 'usuario' : $error = 'desconocido';
            }else {
                $session = new Session();
                $session->set('usuario', $usuario);
                $this->addFlash(
                    'informacion',
                    'Para borrar una librería existente, haz click sobre la librería. Verás fácilmente la que has seleccionado ya que saldrá marcada de otro color. Aparecerá un botón para que la puedas borrar!'
                );
                return $this->redirectToRoute('principal');
            }

        }
        return $this->render('login/login.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/cerrarSesion", name="cerrarSesion")
     */
    public function cerrarSesion(Request $req) {
        $session = $req->getSession();
        $session->clear();
        return $this->redirectToRoute('login');
  
    }
}
