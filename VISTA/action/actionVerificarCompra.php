<?php
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$mail = $_POST['mail'] ?? '';

$objSesion = new Session();
$idUsuario = $objSesion->getUsuario();
$carrito = $objSesion->getCarrito();

$abmCompra = new ABMCompra();
$abmCompraEstado = new ABMCompraEstado();
$abmCompraItem = new ABMCompraItem();

$response = '';

if ($mail == '') {
    $response = ['existe' => false, 'error' => 'No se recibio email'];
    exit;
}

$objAbmUsuario = new ABMUsuario();
$objUsuario = $objAbmUsuario->buscar(['usmail' => $mail]);

if (count($objUsuario) == 0) {
    echo json_encode(['existe' => false, 'msg' => 'Error: no existe un usuario con ese mail']);
    exit;
}

$enviarCorreo = enviarCorreoResumen($mail, $carrito);
if ($enviarCorreo !== true) {
    echo json_encode(['existe' => false, 'msg' => $enviarCorreo]);
    exit;
}
$fechaActual = date("Y-m-d H:i:s");
$paramCompra = [
    "idcompra" => null,
    "cofecha" => $fechaActual,
    "idusuario" => $idUsuario
];

$idNuevaCompra = $abmCompra->alta($paramCompra); 
foreach ($carrito as $idProducto => $cantidad) {
    $paramItem = [
        "idcompraitem" => null,
        "idproducto" => $idProducto,
        "idcompra" => $idNuevaCompra,
        "cicantidad" => $cantidad
    ];
    $abmCompraItem->alta($paramItem);
}
$paramCompraEstado = [
    "idcompraestado" => null,
    "idcompra" => $idNuevaCompra,
    "idcompraestadotipo" => 1, //iniciada default
    "cefechaini" => $fechaActual,
    "cefechafin" => null
];
$abmCompraEstado->alta($paramCompraEstado);

$objSesion->limpiarCarrito();

$response = ['existe' => true, 'msg' =>'Compra exitosa, se envio el resumen del pedido a tu correo'];

echo json_encode($response);
exit;

