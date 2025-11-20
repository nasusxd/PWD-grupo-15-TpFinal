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
    $response = ["success" => false, "msg" =>'no hay estado'];
    echo json_encode($response);
    exit;
}

$ultimoEstado = end($objCompraEstado);
$compra = $objAbmCompra->buscar(['idcompra' => $idCompra]);
$usuario = $objAbmUsuario->buscar(["idusuario" => $compra[0]->getIdUsuario()])[0];


$nombre = $usuario->getNombre();
$email = $usuario->getMail();

switch ($datos['estado']) {
    case '2':
        if ($objAbmCompraEstado->actualizarEstado($ultimoEstado, $idCompra, 2)) {
            $estado = "Aceptada";
            $response = ["success" => true, "msg" => "Estado actualizado a aceptado.", "nuevoEstadoTexto" => $estado];
            enviarCorreoCambioEstado($email,$nombre,$idCompra, $estado);
        } else { 
            $response = [ "success" => false, "msg" => "Error al actualizar a aceptado."];
        }
    break;

    case '3':
        if ($objAbmCompraEstado->actualizarEstado($ultimoEstado, $idCompra, 3)) {
            $estado = "Enviada";
            enviarCorreoCambioEstado($email,$nombre,$idCompra, $estado);
            $response = ["success" => true, "msg" => "Estado actualizado a enviado.", "nuevoEstadoTexto" => $estado ];
        } else {
            $response = ["success" => false, "msg" => "Error al actualizar a enviado."];
        }
    break;

    case '4':
        if ($objAbmCompraEstado->actualizarEstado($ultimoEstado, $idCompra, 4)) {
            $estado = "Cancelada";
            enviarCorreoCambioEstado($email,$nombre,$idCompra, $estado);
            $response = ["success" => true, "msg" => "Estado actualizado a cancelado.", "nuevoEstadoTexto" => $estado];
        } else {
            $response = ["success" => false, "msg" => "Error al actualizar a cancelado."];
        }
    break;
    default: 
    $response["msg"] = "Accion no valida.";
    break;
}


echo json_encode($response);
exit;