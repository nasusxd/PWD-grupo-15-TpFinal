<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$abmProducto = new ABMProducto();
echo json_encode($abmProducto->actualizarDescuento(dataSubmitted()));
