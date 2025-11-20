<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = dataSubmitted();

if (!isset($datos['idproducto']) || !isset($datos['descuento'])) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Faltan datos."
    ]);
    exit;
}

$idProducto = intval($datos['idproducto']);
$descuento  = floatval($datos['descuento']);

// Validación
if ($idProducto <= 0 || $descuento < 0 || $descuento > 100) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Datos inválidos."
    ]);
    exit;
}

$abmProducto = new ABMProducto();

// Buscar si existe
$producto = $abmProducto->buscar(["idproducto" => $idProducto]);

if (empty($producto)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "El producto no existe."
    ]);
    exit;
}

// Actualizar descuento
$exito = $abmProducto->modificarDescuento($idProducto, $descuento);

if ($exito) {
    echo json_encode([
        "success" => true,
        "mensaje" => "Descuento actualizado con éxito."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "No se pudo guardar el descuento."
    ]);
}
