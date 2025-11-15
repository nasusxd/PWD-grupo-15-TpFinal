<?php
header('Content-Type: application/json'); 
include_once '../../configuracion.php';
$datos = datasubmitted();

$session = new Session();
$login = $session->iniciar($datos['usmail'], $datos['uspass']);
$response = ["success" => false, "msg" => "Usuario o contraseña incorrectos, o cuenta deshabilitada."]; //mensaje por defecto
//evaluo si el login fue exitoso
if ($login) {
    $response = ["success" => true, "redirect" => "menu.php"];
}
echo json_encode($response); //mando la respuesta en json
exit;
