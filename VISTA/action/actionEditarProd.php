<?php 
header('Content-Type: application/json'); 
include_once "../../configuracion.php";
$response = ["success" => false, "msg" => "Producto no encontrado o error al buscar el producto."]; 
$datos = datasubmitted();
$objProducto = new ABMProducto();
$objProducto->modificacion($datos);

if ($objProducto) {
    $response = ["success" => true, "msg" => "Producto modificado."]; 
} 
echo json_encode($response);
exit;