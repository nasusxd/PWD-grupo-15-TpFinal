<?php 
header('Content-Type: application/json'); 
include_once '../../configuracion.php';
 $datos = datasubmitted(); 
 $sesion = new Session(); 

  if (!$datos['idproducto'] || $datos['cantidad'] <=0) { 
    echo json_encode([ "success" => false, "error" => "Datos inválidos" ]); 
    exit; 
    } 
    $sesion->agregarAlCarrito($datos['idproducto'], $datos['cantidad']);
    $totalProductos = $sesion->totalProductosCarrito();
    $totalPrecio = $sesion->precioTotalCarrito();
    echo json_encode([ 
        "success" => true, 
        "totalProductos" => $totalProductos,
        "totalPrecio" => $totalPrecio ]); 
    ?>