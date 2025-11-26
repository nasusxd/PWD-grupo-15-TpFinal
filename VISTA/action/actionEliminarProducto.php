<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = datasubmitted();

$idProducto = $datos['id'] ?? null;

$objAbm = new ABMProducto();

$exito = $objAbm->eliminarProducto($idProducto);

if ($exito) {
    $response = ["success" => true, "msg" => "Producto eliminado con éxito."];
} else {
    $response = ["success" => false, "msg" => "No se pudo eliminar el producto en la BD."];
}

echo json_encode($response);
exit;
