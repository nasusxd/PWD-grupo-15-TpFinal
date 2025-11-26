<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';

$datos = datasubmitted();
$abmUsuario = new ABMUsuario();
$session = new Session();

$response = ["success" => false, "msg" => "Usuario o contraseña incorrectos, o cuenta deshabilitada."];

$usuario = $abmUsuario->login($datos['usmail'], $datos['uspass']);

if ($usuario) {
    // Iniciar la sesión con el usuario
    $session->crearSessionConUsuario($usuario);

    if ($session->esAdmin()) {
        $response = ["success" => true, "redirect" => "index.php"];
    } else {
        $response = ["success" => true, "redirect" => "menu.php"];
    }
}

echo json_encode($response);
exit;
