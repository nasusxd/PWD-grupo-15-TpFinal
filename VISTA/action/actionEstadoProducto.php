<?php
include_once "../../configuracion.php";

header("Content-Type: application/json");

$id = $_POST["id"] ?? null;
$accion = $_POST["action"] ?? null;

if (!$id || !$accion) {
    echo json_encode(["success" => false, "message" => "Faltan datos."]);
    exit;
}

$objAbmProducto = new ABMProducto();
$resultado = $objAbmProducto->cambiarEstado($id, $accion);

echo json_encode($resultado);
