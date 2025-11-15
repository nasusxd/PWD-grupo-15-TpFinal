<?php
session_start();

// Carpeta raíz del proyecto en el servidor
$GLOBALS['ROOT'] = __DIR__ . '/';

// URL base de la aplicación
$host = $_SERVER['HTTP_HOST'];
$uri  = '/tpfinal/PWD-grupo-15-TpFinal/VISTA'; // carpeta de tus vistas
define('BASE_URL', "http://$host$uri/");

// Guardar ROOT en sesión
$_SESSION['ROOT'] = $GLOBALS['ROOT'];

// Incluir funciones globales
include_once($GLOBALS['ROOT'] . 'UTILS/funciones.php');