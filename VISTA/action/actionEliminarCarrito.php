<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';

$carritoABM = new ABMCarrito();

$idProducto = $_POST['idproducto'] ?? null;

if ($idProducto !== null) {
    $carritoABM->eliminarProducto($idProducto);
}

$items = $carritoABM->getItemsDetalle();

echo json_encode([
    'success' => true,
    'items' => $items
]);
exit;
