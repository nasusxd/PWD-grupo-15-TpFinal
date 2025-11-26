<?php 
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$datos = datasubmitted(); 
$objAbm = new ABMProducto();

$resultado = $objAbm->modificarProducto($datos);

echo json_encode($resultado);
exit;
?>
