<?php
header('Content-Type: application/json');
include_once '../../configuracion.php';

$datos = datasubmitted();
$abmCorreo = new ABMCorreo();   

echo json_encode($abmCorreo->enviarConsulta($datos));
exit;
