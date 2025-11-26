<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$abm = new ABMCompraEstado();
echo json_encode($abm->cambiarEstadoCompra(datasubmitted()));
