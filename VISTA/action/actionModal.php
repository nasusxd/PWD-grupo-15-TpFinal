<?php
include_once "../../configuracion.php";

$sesion = new Session();
$carrito = $sesion->getCarrito();
$items = [];
$totalProductos = $sesion->totalProductosCarrito();

if ($carrito) {
    $abmProducto = new ABMProducto();

    foreach ($carrito as $id => $cantidad) {
        $prod = $abmProducto->buscar(['idproducto' => $id])[0];

        $items[] = [
            'id' => $id,
            'nombre' => $prod->getNombre(),
            'cantidad' => $cantidad
        ];
    }
}

echo json_encode([
    "success" => true,
    "items" => $items,
    "total" => $totalProductos,
    "precioTotal" => $sesion->precioTotalCarrito()
]);
