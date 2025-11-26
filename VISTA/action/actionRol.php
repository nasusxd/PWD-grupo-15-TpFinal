<?php
include_once "../../configuracion.php";

$datos = dataSubmitted();
$objAbmRol = new ABMRol();

$response = ["success" => false];

if (isset($datos['accion'])) {

    if ($datos['accion'] === 'nuevo') {
        $response = $objAbmRol->crearRol($datos['rodescripcion']);
    }

    if ($datos['accion'] === 'borrar') {
        $response = $objAbmRol->borrarRol($datos['idrol']);
    }
}

echo json_encode($response);
exit;
