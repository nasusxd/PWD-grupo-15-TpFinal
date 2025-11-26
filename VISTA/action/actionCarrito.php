<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';
include_once '../../CONTROL/AbmProducto.php';
include_once '../../UTILS/funciones.php';

$datos = datasubmitted();

$abm = new ABMProducto();
echo json_encode($abm->agregarAlCarrito($datos));
exit;
