<?php
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$response = ["success" => false, "msg" => "Accion no valida"];

$datos = datasubmitted();
$idCompra =  $datos['idcompra'];
$nuevoEstado = (int)$datos['estado'];
if (!$idCompra || !$nuevoEstado) {
    echo json_encode(["success" => false, "msg" => "Datos incompletos"]);
    exit;
}

$objAbmCompraEstado = new ABMCompraEstado();
$objAbmUsuario = new ABMUsuario();
$objAbmCompra = new ABMCompra();
$objAbmProducto = new ABMProducto();
$objAbmCompraItem = new ABMCompraItem();

$estados = $objAbmCompraEstado->buscar(['idcompra' => $idCompra]);

if (empty($estados)) {
    $response = ["success" => false, "msg" =>'No hay estado registrado para esta compra'];
    echo json_encode($response);
    exit;
}

$ultimoEstado = end($estados);
$estadoActual = $ultimoEstado->getIdCompraEstadoTipo(); 

$compra = $objAbmCompra->buscar(['idcompra' => $idCompra]);
$usuario = $objAbmUsuario->buscar(["idusuario" => $compra[0]->getIdUsuario()])[0];
$nombre = $usuario->getNombre();
$email = $usuario->getMail();

if ($estadoActual == 3) {
    $response["msg"] = "No se puede cambiar una compra ya enviada.";
    echo json_encode($response);
    exit;
}

if ($estadoActual == 2 && $nuevoEstado == 4 || $estadoActual == 3 && $nuevoEstado == 4 || $estadoActual == 4) {
    $response["msg"] = "No se puede cancelar una compra aceptada.";
    echo json_encode($response);
    exit;
}

if ($estadoActual == 1 && !in_array($nuevoEstado, [2,4])) {
    $response["msg"] = "Cambio de estado inválido desde iniciada.";
    echo json_encode($response);
    exit;
}

if ($estadoActual == 2 && $nuevoEstado != 3) {
    $response["msg"] = "Cambio de estado inválido desde aceptada.";
    echo json_encode($response);
    exit;
}

$modificarRegistro = [
    "idcompraestado" => $ultimoEstado->getIdCompraEstado(),
    "idcompra" => $idCompra,
    "idcompraestadotipo" => $ultimoEstado->getIdCompraEstadoTipo(),
    "cefechaini" => $ultimoEstado->getFechaIni(),
    "cefechafin" => date("Y-m-d H:i:s")
];
$res = $objAbmCompraEstado->modificacion($modificarRegistro);
if (!$res) {
    echo json_encode(["success" => false, "msg" => "Error al actualizar el estado anterior"]);
    exit;
}
$nuevoRegistro = [
    "idcompraestado" => null,
    "idcompra" => $idCompra,
    "idcompraestadotipo" => $nuevoEstado,
    "cefechaini" => date("Y-m-d H:i:s"),
    "cefechafin" => null
];

$creado = $objAbmCompraEstado->alta($nuevoRegistro);

if (!$creado) {
    echo json_encode(["success" => false, "msg" => "Error al registrar el nuevo estado"]);
    exit;
}

if ($nuevoEstado == 4) {
    $items = $objAbmCompraItem->buscar(['idcompra' => $idCompra]);

    foreach ($items as $item) {
        $idProducto = $item->getIdProducto();
        $cantidad = $item->getCantidad();

        $producto = $objAbmProducto->buscar(["idproducto" => $idProducto]);

        $nuevoStock = $producto[0]->getStock() + $cantidad;
        $param = [
            "idproducto" => $idProducto,
            "pronombre" => $producto[0]->getNombre(),
            "prodetalle" => $producto[0]->getDetalle(),
            "precio" => $producto[0]->getPrecio(),
            "procantstock" => $nuevoStock,
            "proimagen" => $producto[0]->getImagen(),
            'descuento' => $producto[0]->getDescuento()
        ];
        $res = $objAbmProducto->modificacion($param);
        $productoNuevo = $objAbmProducto->buscar(["idproducto" => $idProducto]);
    }
}

$estadoTexto = match($nuevoEstado) {
    1 => "Iniciada",
    2 => "Aceptada",
    3 => "Enviada",
    4 => "Cancelada",
    default => "Desconocido",
};

enviarCorreoCambioEstado($email, $nombre, $idCompra, $estadoTexto);

$response = [
    "success" => true,
    "msg" => "Estado actualizado correctamente",
    "nuevoEstadoTexto" => $estadoTexto
];

echo json_encode($response);
exit;
