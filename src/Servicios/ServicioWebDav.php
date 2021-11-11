<?php

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ServicioWebDav {

    public function __construct(){}

    /**
     * Función que crea una carpeta en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param path El path base del usuario
     * @param nombreCarpeta El nombre de la carpeta (puede ser un subdirectorio ej: datos/pruebas)
     * 
     *  */ 
    public function crearCarpetas($nombreUsuario, $password, $path, $nombreCarpeta)
    {
        
        $settings = [
            'baseUri' =>  $path,
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $response = $flysystem->createDir($nombreCarpeta);
    }

    /**
     * Función que crea un archivo en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio EL directorio dónde se va a generar el archivo
     * @param texto El contenido del fichero
     * 
     */
    public function crearArchivo($nombreUsuario, $password, $nombreFichero, $texto)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $response = $flysystem->write('/'.$nombreFichero, $texto);
    }

    /**
     * Función que actualiza un archivo en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio EL directorio dónde se va a generar el archivo
     * @param texto El contenido del fichero
     * 
     */
    public function actualizarArchivo($nombreUsuario, $password, $directorio, $texto)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $response = $flysystem->update($directorio, $texto);
    }

    /**
     * Función que lee un fichero del directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio El directorio dónde se encuentra el fichero a leer
     * 
     */
    public function leerArchivo($nombreUsuario, $password, $directorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $contents = $flysystem->readStream($directorio);
        return $contents;
    }

    /**
     * Función que lee un fichero del directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio El directorio dónde se encuentra el fichero a leer
     * 
     */
    public function leerContenidosArchivo($nombreUsuario, $password, $directorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $contents = $flysystem->read($directorio);
        return $contents;
    }

    

    /**
     * Función que valida si un fichero o un directorio existen en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio El directorio a validar
     * 
     * @return existe True si existe, false si no existe
     * 
     */
    public function validarDirectorio($nombreUsuario, $password, $directorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $existe = $flysystem->has($directorio);

        return $existe;
    }

    /**
     * Función que borra un fichero o un directorio en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio El directorio a eliminar
     * 
     * 
     */
    public function eliminarFichero($nombreUsuario, $password, $directorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $response = $flysystem->delete($directorio);

    }

    /**
     * Función que renombra un fichero o un directorio en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio El directorio a renombrar
     * @param nuevoDirectorio El nuevo nombre del directorio
     * 
     * 
     */
    public function renombrarDirectorio($nombreUsuario, $password, $directorio, $nuevoDirectorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);
        $response = $flysystem->rename($directorio, $nuevoDirectorio);

    }

    /**
     * Función que copia un fichero en el directorio de ficheros del usuario a otro directorio
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio La ruta del fichero a copiar
     * @param nuevoDirectorio El nombre del directorio dónde se copiará el archivo
     * 
     * 
     */
    public function copiaFichero($nombreUsuario, $password, $directorio, $nuevoDirectorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $response = $flysystem->copy($directorio, $nuevoDirectorio);

    }

    /**
     * Función que elimina un directorio de manera recursiva en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio La ruta del directorio a eliminar
     * 
     * 
     */
    public function eliminarDirectorio($nombreUsuario, $password, $directorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/owncloud/remote.php/dav/files/'.$nombreUsuario.'/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, '');
        $flysystem = new \League\Flysystem\Filesystem($adapter);

        $response = $flysystem->deleteDir($directorio);

    }

    /**
     * Función que lista el contenido de un directorio en el directorio de ficheros del usuario
     * 
     * @param nombreUsuario El nombre del usuario
     * @param password La contraseña del usuario
     * @param directorio La ruta del directorio a listar
     * 
     * @return response La lista con todos los elementos del directorio
     * 
     */
    public function listarDirectorio($nombreUsuario, $password, $directorio)
    {
        
        $settings = [
            'baseUri' => 'http://localhost/',
            'userName' => $nombreUsuario,
            'password' => $password,
        ];
        $client = new \Sabre\DAV\Client($settings);
        $adapter = new \League\Flysystem\WebDAV\WebDAVAdapter($client, 'owncloud/remote.php/dav/files/'.$nombreUsuario.'/');
        $flysystem = new \League\Flysystem\Filesystem($adapter);
        $response = $flysystem->listContents('/', true);
        return $response;

    }




}















?>