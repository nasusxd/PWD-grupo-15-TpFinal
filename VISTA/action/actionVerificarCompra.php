<?php
header('Content-Type: application/json'); 
include_once "../../configuracion.php";
$mail = $_POST['mail'] ?? '';
$objSesion = new Session();
$carrito = $objSesion->getCarrito();

$response = ['existe' => false, 'error' => 'No se recibió email'];
if ($mail == '') {
    echo json_encode(['existe' => false, 'error' => 'No se recibió email']);
    exit;
} else {
    $objAbmUsuario = new ABMUsuario();
    $usuario = $objAbmUsuario->buscar(['usmail' => $mail]);
    if (count($usuario) > 0) {
        $enviarCorreo = enviarCorreoResumen($mail, $carrito);
        if ($enviarCorreo === true) {
            $response = ['existe' => true, 'msg' => 'Compra exitosa, se mando el resumen del pedido a tu correo'];
            $objSesion->limpiarCarrito();
        } else {
            $response = ['existe' => false, 'msg' => $enviarCorreo];
        }
    } else {
        $response = ['existe' => false, 'msg' =>'Error al buscar el mail del usuario'];
    }
}

echo json_encode($response);
exit;

