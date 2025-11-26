<?php
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$mail = $_POST['mail'] ?? '';

$session = new Session();
$idUsuario = $session->getUsuario();
$carrito = $session->getCarrito();

$abmCompra = new ABMCompra();

$response = $abmCompra->procesarCompra($mail, $idUsuario, $carrito);

// Si la compra fue exitosa → limpiar carrito
if ($response['success']) {
    $session->limpiarCarrito();
}

echo json_encode($response);
exit;
