<?php

class ServicioCURL {

    private const URL='http://owncloud:owncloud@localhost/owncloud/ocs/v1.php/cloud/users/';

public function __construct(){}

/**
 * Función que devuelve la capacidad de almacenamiento del usuario, tanto la usada como la total
 * 
 * @param nombreUsuario El nombre del usuario del que se obtendrán los datos
 * 
 * @return informacionUsuario Los datos de almacenamiento del usuario en cuestión
 * 
*/
public function getInformacionCapacidadUsuario($nombreUsuario) {

    $url = self::URL.$nombreUsuario;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPGET,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $xml = new SimpleXMLElement($response);
    $elementos = $xml->xpath('/ocs/data/quota');
    foreach ($elementos as $elemento) {
        $total = $elemento->total;
        $usado = $elemento->used;
    }
    $totalGB = $total*9.313225746154785*pow(10,-10);
    $usadoGB = $usado*9.313225746154785*pow(10,-10);

    $informacionUsuario = array("totalGB" => $totalGB, "usadoGB" => $usadoGB);
    return $informacionUsuario;
}

/**
 * Función devuelve el árbol de directorios del usuario
 * 
 * @param nombreUsuario El nombre del usuario del que se obtendrán los datos
 * 
 * @return directoriosUsuario Los directorios del usuario
 * 
*/
public function getArbolDirectoriosUsuario($nombreUsuario) {
    $url = self::URL.$nombreUsuario.'?format=json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPGET,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($response, true);
    $directoriosUsuario = $json["ocs"]["data"]["home"];
    return $directoriosUsuario;


}
/**
 * Función que crea un nuevo usuario en Owncloud
 * 
 * @param nombreUsuario El nombre del usuario
 * 
 * @param password La contraseña del usuario
 * 
*/
public function crearNuevoUsuario($nombreUsuario, $password) {
    $ch = curl_init(self::URL);
    $array = array('userid' => $nombreUsuario, 'password' => $password);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$array);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch); 
}

}


?>