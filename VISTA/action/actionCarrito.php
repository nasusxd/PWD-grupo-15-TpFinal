<?php
session_start();

// Tomar datos enviados por AJAX (POST)
$idProducto = $_POST['idproducto'] ?? null;
$cantidad   = $_POST['cantidad'] ?? 1;

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Aumentar cantidad del producto
if (!isset($_SESSION['carrito'][$idProducto])) {
    $_SESSION['carrito'][$idProducto] = 0;
}

$_SESSION['carrito'][$idProducto] += $cantidad;

// Calcular total de productos
$_SESSION['total_carrito'] = array_sum($_SESSION['carrito']);

// Construir lista de items para el modal
$items = [];

foreach ($_SESSION['carrito'] as $id => $cant) {
    $items[] = [
        "id" => $id,
        "nombre" => "Producto $id", // POR AHORA, hasta usar BD real
        "cantidad" => $cant
    ];
}

// Respuesta JSON
echo json_encode([
    "success" => true,
    "total" => $_SESSION['total_carrito'],
    "items" => $items
]);
