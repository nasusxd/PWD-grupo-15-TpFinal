<?php
header('Content-Type: application/json'); 
include_once '../../configuracion.php';
// Tomar datos enviados por AJAX (POST)
$datos = datasubmitted();
$sesion = new Session();
$idProducto = $_POST['idproducto'] ?? null;
$cantidad   = $_POST['cantidad'] ?? 1;

if (!$datos['idproducto'] || $datos['cantidad'] <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "Datos inválidos"
    ]);
    exit;
}

$sesion->agregarAlCarrito($idProducto, $cantidad);
$totalProductos = $sesion->totalProductosCarrito();
$totalPrecio = $sesion->precioTotalCarrito();

// Respuesta en JSON
echo json_encode([
    "success" => true,
    "totalProductos" => $_SESSION['total_carrito'],
    "totalPrecio" => $totalPrecio
]);
?>
