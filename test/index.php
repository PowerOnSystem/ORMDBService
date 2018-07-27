<?php
/*
 * Archivo de testeo del framework
 */
session_start();

define('ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

require ROOT . DS . 'vendor' . DS . 'autoload.php';

try {
    try {
        //Creamos un servicio PDO para la base de datos MySQL con la clase que viene en la libreria
        $service = new PDO(sprintf('mysql:host=%s;dbname=%s;port=%s', 'localhost', 'poweron', 3306), 'root', '');
    } catch (PDOException $e) {
        throw new PowerOn\Database\DataBaseServiceException($e->getMessage(), ['pdo' => $e]);
    }
    //Creamos el controlador de la base de datos
    $database = new PowerOn\Database\ORM\Manager( $service, ['tables_namespace' => '\App\\'] );
    /* @var $usersTable \App\Tables\Users */
    $usersTable = $database->get('users');
    
    !d($users);
    
    !d($usersTable->model()->debug(PowerOn\Database\Model::DEBUG_QUERIES));
} catch (\PowerOn\Database\DataBaseServiceException $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
    !d($e->getContext());
}