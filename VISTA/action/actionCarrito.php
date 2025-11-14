<?php
session_start();

// Tomar datos enviados por AJAX (POST)
$idProducto = $_POST['idproducto'] ?? null;
$cantidad   = $_POST['cantidad'] ?? 1;

// Inicializar el contador si no existe
if (!isset($_SESSION['total_carrito'])) {
    $_SESSION['total_carrito'] = 0;
}

// Aumentar el total del carrito
$_SESSION['total_carrito'] += $cantidad;

// Respuesta en JSON
echo json_encode([
    "success" => true,
    "total" => $_SESSION['total_carrito']
]);
?>
