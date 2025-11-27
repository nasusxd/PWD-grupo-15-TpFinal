<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$mail = $_POST['mail'] ?? "";
$session = new Session();
$abmUsuario = new ABMUsuario();
$abmCompra = new ABMCompra();

$idUsuario = $session->getUsuario();
$carrito = $session->getCarrito();
$response = $abmCompra->procesarCompra($mail, $idUsuario, $carrito);
$usuario = $abmUsuario->buscar(["usmail" => $mail]);

if ($response['success']) {
    $session->limpiarCarrito();
}

echo json_encode([
    "existe" => !empty($usuario)
]);
exit;
