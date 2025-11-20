<?php 
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$datos = datasubmitted(); 
$objAbm = new ABMProducto();

$listaProductos = $objAbm->buscar(['idproducto' => $datos['idproducto']]); //rec

if (count($listaProductos) > 0) {
    $productoActual = $listaProductos[0];
    
    $datos['proimagen'] = $productoActual->getImagen();
}
$exito = $objAbm->modificacion($datos);
if ($exito) {
    $response = ["success" => true, "msg" => "Producto modificado."];
} else {
    $response = ["success" => false, "msg" => "No se pudo modificar el producto en la BD."];
}

echo json_encode($response);
exit;
?>