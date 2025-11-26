<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = datasubmitted();
$resp = ['success' => false, 'msg' => 'No se pudo realizar la acción.'];

if (isset($datos['idusuario']) && isset($datos['accion'])) {

    $session = new Session();
    $abmUsuario = new ABMUsuario();

    // Evitar deshabilitarse a uno mismo
    if ($datos['idusuario'] == $session->getUsuario()) {
        echo json_encode(['success' => false, 'msg' => 'No puedes deshabilitar tu propio usuario.']);
        exit;
    }

    $ok = $abmUsuario->cambiarEstadoUsuario($datos['idusuario'], $datos['accion']);

    if ($ok) {
        $resp['success'] = true;
        $resp['msg'] = "Estado actualizado correctamente.";
    } else {
        $resp['msg'] = "Usuario no encontrado o acción inválida.";
    }
}

echo json_encode($resp);
exit;
?>
