<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';

$datos = datasubmitted();

$abmUsuario = new ABMUsuario();
$resultado = $abmUsuario->altaCompleta($datos);

if ($resultado["success"]) {
    echo json_encode(["success" => true, "redirect" => "login.php"]);
} else {
    echo json_encode($resultado);
}

exit;
