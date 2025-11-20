<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';
include_once '../../CONTROL/AbmProducto.php';
include_once '../../UTILS/funciones.php';
$sesion = new Session();
$datos = datasubmitted();

if (!$datos['idproducto'] || $datos['cantidad'] <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "Datos inválidos"
    ]);
    exit;
}

$objProducto = new ABMProducto();
$productos = $objProducto->buscar(['idproducto' => $datos['idproducto']]);

if (empty($productos)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Producto inexistente"
    ]);
    exit;
}

$producto = $productos[0];
$stockDisponible = $producto->getStock();

$cantidadEnCarrito = $_SESSION['carrito'][$datos['idproducto']] ?? 0;

$cantidadTotal = $cantidadEnCarrito + $datos['cantidad'];

if ($cantidadTotal > $stockDisponible) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Stock insuficiente. Solo hay $stockDisponible unidades disponibles."
    ]);
    exit;
}

$sesion->agregarAlCarrito($datos['idproducto'], $datos['cantidad']);

$totalProductos = $sesion->totalProductosCarrito();
$totalPrecio = $sesion->precioTotalCarrito();

$items = [];

if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
        $productosBuscados = $objProducto->buscar(['idproducto' => $idProducto]);

        if (count($productosBuscados) > 0) {
            $productoEncontrados = $productosBuscados[0];

            $descuento = $productoEncontrados->getDescuento(); 
            
            if ($descuento > 0) {
                $precioUnitario = $productoEncontrados->getPrecio() * (1 - $descuento / 100);
            } else {
                $precioUnitario = $productoEncontrados->getPrecio();
            }

            $items[] = [
                "id" => $idProducto,
                "nombre" => $productoEncontrados->getNombre(),
                "cantidad" => $cantidad,
                "precioUnitario" => $precioUnitario,
                "subtotal" => $precioUnitario * $cantidad,
                "descuento" => $descuento
            ];
        }
    }
}

echo json_encode([
    "success" => true,
    "totalProductos" => $totalProductos,
    "totalPrecio" => $totalPrecio,
    "items" => $items
]);
