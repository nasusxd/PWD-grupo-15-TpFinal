<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';
include_once '../../CONTROL/AbmProducto.php';
include_once '../../UTILS/funciones.php';
$sesion = new Session();
$datos = datasubmitted();

// Validar datos
if (!$datos['idproducto'] || $datos['cantidad'] <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "Datos inválidos"
    ]);
    exit;
}

// Agregar al carrito usando la clase Session
$sesion->agregarAlCarrito($datos['idproducto'], $datos['cantidad']);

// Obtener totales
$totalProductos = $sesion->totalProductosCarrito();
$totalPrecio = $sesion->precioTotalCarrito();

// Construir lista de items con nombre y cantidad
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

// Devolver JSON completo
echo json_encode([
    "success" => true,
    "totalProductos" => $totalProductos,
    "totalPrecio" => $totalPrecio,
    "items" => $items
]);
