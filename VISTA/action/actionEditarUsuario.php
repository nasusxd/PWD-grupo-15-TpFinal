<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

$datos = datasubmitted();
$abmUsuario = new ABMUsuario();

$respuesta = $abmUsuario->modificarUsuarioConRol($datos);

echo json_encode($respuesta);
exit;
