<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = dataSubmitted();

if (!$datos['idcompra']) {
    echo json_encode(["success" => false, "msg" => "ID de compra no recibido"]);
    exit;
}

$abm = new ABMCompraEstado();
$respuesta = $abm->obtenerHistorialCompra($datos['idcompra']);

echo json_encode($respuesta);
exit;
