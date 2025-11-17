<?php
header('Content-Type: application/json'); 
include_once '../../configuracion.php';
$datos = datasubmitted();
$enviarMail = enviarCorreo($datos['correo'], $datos['asunto'], $datos['nombre'], $datos['mensaje']);
$response = ["success" => false, "msg" => 'Error al querer recibir la consulta']; 
if ($enviarMail === true) {
    $response = ["success" => true, "msg" => 'Se recibio tu consulta']; 
} else {
    $response = ["success" => false, "msg" => 'Error'. $enviarMail]; 
}
echo json_encode($response); //mando la respuesta en json
exit;