<?php

require_once 'config/configuracion.php';
require_once 'aplicacion/controladores/apicontrolador.php';

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$recurso = '';
if (!empty( $_GET['resource'])) {
    $recurso = $_GET['resource'];
}

$params = explode('/', $recurso);
$recursoPrincipal = $params[0];

switch ($recursoPrincipal) {
    case 'reseñas':
        $controller = new apicontrolador();
        $controller->manejarReseñas($params);
        break;

    default:
        require_once 'aplicacion/vistas/apivista.php';
        $vista = new apivista();
        $vista->respuesta("Recurso no encontrado", 404);
        break;
}