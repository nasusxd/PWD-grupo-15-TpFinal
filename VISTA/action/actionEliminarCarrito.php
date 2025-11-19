<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';
$objSession = new Session();

$idProducto = $_POST['idproducto'] ?? null;

if ($idProducto !== null && isset($_SESSION['carrito'][$idProducto])) {
    unset($_SESSION['carrito'][$idProducto]);
}

$items = [];

if (isset($_SESSION['carrito'])) {
    $objProducto = new ABMProducto();
    foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
        $productos = $objProducto->buscar(['idproducto' => $idProducto]);
        if (count($productos) > 0) {
            $producto = $productos[0];
            $items[] = [
                "id" => $idProducto,
                "nombre" => $producto->getNombre(),
                "cantidad" => $cantidad,
                "precioUnitario" => $producto->getPrecio(),
                "subtotal" => $producto->getPrecio() * $cantidad
            ];
        }
    }
}

echo json_encode([
    'success' => true,
    'items'   => $items,
]);
exit;
