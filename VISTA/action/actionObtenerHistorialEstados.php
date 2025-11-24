<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = dataSubmitted();

if (!$datos['idcompra']) {
    echo json_encode(["success" => false, "msg" => "ID de compra no recibido"]);
    exit;
}

$objAbmCompraEstado = new ABMCompraEstado();
$objAbmCompraEstadoTipo = new ABMCompraEstadoTipo();

$estados = $objAbmCompraEstado->buscar(['idcompra' => $datos['idcompra']]);

if (empty($estados)) {
    echo json_encode(["success" => false, "msg" => "No se encontraron estados para esta compra"]);
    exit;
}

$data = [];

foreach ($estados as $estado) {

    $tipo = $objAbmCompraEstadoTipo->buscar([
        "idcompraestadotipo" => $estado->getIdCompraEstadoTipo()
    ]);

    $descripcion = !empty($tipo) ? $tipo[0]->getDescripcion() : "Desconocido";

    $data[] = [
        "idcompraestado" => $estado->getIdCompraEstado(),
        "estado" => $estado->getIdCompraEstadoTipo(),
        "descripcion" => $descripcion,
        "inicio" => $estado->getFechaIni(),
        "fin" => $estado->getFechaFin()
    ];
}

usort($data, function ($a, $b) {
    return strtotime($a["inicio"]) <=> strtotime($b["inicio"]);
});

echo json_encode([
    "success" => true,
    "data" => $data
]);
exit;
