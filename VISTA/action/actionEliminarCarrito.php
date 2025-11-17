<?php
session_start();

$idProducto = $_POST['idproducto'] ?? null;

if ($idProducto !== null && isset($_SESSION['carrito'][$idProducto])) {
    unset($_SESSION['carrito'][$idProducto]);
}

$total = 0;
$items = [];

if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $id => $item) {
        $total += $item['cantidad'];
        $items[] = [
            'id'       => $id,
            'nombre'   => $item['nombre'],
            'cantidad' => $item['cantidad']
        ];
    }
}

echo json_encode([
    'success' => true,
    'items'   => $items,
    'total'   => $total
]);
exit;
