<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$mail = $_POST['mail'] ?? "";

$abmUsuario = new ABMUsuario();
$usuario = $abmUsuario->buscar(["usmail" => $mail]);

$session = new Session();


$session->limpiarCarrito();

echo json_encode([
    "existe" => !empty($usuario)
]);
exit;
