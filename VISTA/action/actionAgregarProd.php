<?php
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$datos = datasubmitted();
$abmProducto = new ABMProducto();

// Llamamos a un método del ABM que maneja TODO
$resultado = $abmProducto->altaConImagen($datos, $_FILES);

echo json_encode($resultado);
exit;
