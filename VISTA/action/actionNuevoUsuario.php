<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = datasubmitted();

$abmUsuario = new ABMUsuario();
$respuesta = $abmUsuario->registrarUsuario($datos);

echo json_encode($respuesta);
exit;
