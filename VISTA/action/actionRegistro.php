<?php
header('Content-Type: application/json'); 
include_once '../../CONTROL/AbmUsuarioRol.php';
include_once '../../UTILS/funciones.php';
include_once '../../configuracion.php';

$datos = datasubmitted();

// respuesta por defecto
$response = ["success" => false, "msg" => ""];

$objUsuario = new ABMUsuario();

$existeEmail = $objUsuario->buscar(['usmail' => $datos['usmail']]);

if (count($existeEmail) > 0) {
    $response = [
        "success" => false, 
        "msg" => "El email ingresado ya esta registrado"
    ];
} else {
    $resultado = $objUsuario->alta($datos);

    if ($resultado) {
        // asignamos el rol de cliente al nuevo usuario
        $objUsuarioRol = new ABMUsuarioRol();
        $idUsuario = $objUsuario->buscar(['usmail' => $datos['usmail']])[0]->getIdUsuario();
        $nuevoUsuarioRol = [
            'idusuario' => $idUsuario,
            'idrol' => 1
        ];
        $objUsuarioRol->alta($nuevoUsuarioRol);
        if ($objUsuarioRol) {
            $response = ["success" => true, "redirect" => "login.php"];
        } else {
            $response = ["success" => false, "msg" => "Error al cargar el rol"];
        }
    } else {
        $response = ["success" => false, "msg" => "Error en el alta del usuario."];
    }
}
echo json_encode($response);
exit;
