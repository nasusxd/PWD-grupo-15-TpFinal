<?php
include_once "../../configuracion.php";

$sesion = new Session();
$abmProducto = new ABMProducto();

$items = $abmProducto->obtenerItemsCarrito($sesion->getCarrito());

echo json_encode([
    "success" => true,
    "items" => $items,
    "total" => $sesion->totalProductosCarrito(),
    "precioTotal" => $sesion->precioTotalCarrito()
]);
