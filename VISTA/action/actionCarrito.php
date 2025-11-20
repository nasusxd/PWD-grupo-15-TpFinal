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


$sesion->agregarAlCarrito($datos['idproducto'], $datos['cantidad']);

$totalProductos = $sesion->totalProductosCarrito();
$totalPrecio = $sesion->precioTotalCarrito();

$items = [];

if (isset($_SESSION['carrito'])) {
    $objProducto = new ABMProducto();
    foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
        $productos = $objProducto->buscar(['idproducto' => $idProducto]);

        if (count($productos) > 0) {
            $producto = $productos[0];

            $descuento = $producto->getDescuento(); 
            
            // CALCULAR PRECIO FINAL
            if ($descuento > 0) {
                $precioUnitario = $producto->getPrecio() * (1 - $descuento / 100);
            } else {
                $precioUnitario = $producto->getPrecio();
            }

            $items[] = [
                "id" => $idProducto,
                "nombre" => $producto->getNombre(),
                "cantidad" => $cantidad,
                "precioUnitario" => $precioUnitario,
                "subtotal" => $precioUnitario * $cantidad,
                "descuento" => $descuento
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
