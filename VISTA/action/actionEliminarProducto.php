<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = datasubmitted(); 

if (isset($datos['id'])) {
    $datos['idproducto'] = $datos['id'];
}


$objAbm = new ABMProducto();



if ($objAbm->baja($datos)) {
    $response = ["success" => true, "msg" => "Producto eliminado con éxito."];
} else {
    $response = ["success" => false, "msg" => "No se pudo eliminar el producto en la BD."];
}

echo json_encode($response);
?>