<?php
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$response = ["success" => false, "msg" => "Accion no valida"];

$datos = datasubmitted();
$idCompra =  $datos['idcompra'];

$objAbmCompraEstado = new ABMCompraEstado();
$objAbmUsuario = new ABMUsuario();
$objAbmCompra = new ABMCompra();
$objAbmProducto = new ABMProducto();
$objAbmCompraItem = new ABMCompraItem();

$objCompraEstado = $objAbmCompraEstado->buscar(['idcompra' => $idCompra]);

if (empty($objCompraEstado)) {
    $response = ["success" => false, "msg" =>'No hay estado registrado para esta compra'];
    echo json_encode($response);
    exit;
}

$ultimoEstado = end($objCompraEstado);
$estadoActual = $ultimoEstado->getIdCompraEstadoTipo(); 
$nuevoEstado = intval($datos['estado']); 

$compra = $objAbmCompra->buscar(['idcompra' => $idCompra]);
$usuario = $objAbmUsuario->buscar(["idusuario" => $compra[0]->getIdUsuario()])[0];
$nombre = $usuario->getNombre();
$email = $usuario->getMail();

if ($estadoActual == 3) {
    $response["msg"] = "No se puede cambiar una compra ya enviada.";
    echo json_encode($response);
    exit;
}

if ($estadoActual == 2 && $nuevoEstado == 4) {
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

switch ($nuevoEstado) {
    case 2: 
        if ($objAbmCompraEstado->actualizarEstado($ultimoEstado, $idCompra, 2)) {
            $estadoTexto = "Aceptada";
            $response = ["success" => true, "msg" => "Estado actualizado a aceptado.", "nuevoEstadoTexto" => $estadoTexto];
            enviarCorreoCambioEstado($email, $nombre, $idCompra, $estadoTexto);
        } else { 
            $response = ["success" => false, "msg" => "Error al actualizar a aceptado."];
        }
        break;

    case 3:
        if ($objAbmCompraEstado->actualizarEstado($ultimoEstado, $idCompra, 3)) {
            $estadoTexto = "Enviada";
            $response = ["success" => true, "msg" => "Estado actualizado a enviado.", "nuevoEstadoTexto" => $estadoTexto];
            enviarCorreoCambioEstado($email, $nombre, $idCompra, $estadoTexto);
        } else {
            $response = ["success" => false, "msg" => "Error al actualizar a enviado."];
        }
        break;

    case 4:
        if ($objAbmCompraEstado->actualizarEstado($ultimoEstado, $idCompra, 4)) {
            $estadoTexto = "Cancelada";
            $response = ["success" => true, "msg" => "Estado actualizado a cancelado.", "nuevoEstadoTexto" => $estadoTexto];
            enviarCorreoCambioEstado($email, $nombre, $idCompra, $estadoTexto);
        } else {
            $response = ["success" => false, "msg" => "Error al actualizar a cancelado."];
        }
        break;

    default: 
        $response["msg"] = "Acción no válida.";
        break;
}

echo json_encode($response);
exit;
