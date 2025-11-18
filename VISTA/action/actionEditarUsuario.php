<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

$response = ['success' => false, 'msg' => 'Error al querer modificar el usuario'];

$datos = datasubmitted();

$abmUsuario = new ABMUsuario();
$abmUsuarioRol = new ABMUsuarioRol();

$id = $datos['idusuario'] ?? null;
$nuevoPass = $datos['uspass'] ?? null;
$rolNuevo = $datos['idrol'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'msg' => 'ID de usuario no recibido']);
    exit;
}

// 1) BUSCAR USUARIO
$busquedaUsuario = $abmUsuario->buscar(['idusuario' => $id]);
if (!$busquedaUsuario) {
    echo json_encode(['success' => false, 'msg' => 'Usuario no encontrado']);
    exit;
}

$usuario = $busquedaUsuario[0];

// 2) ARMAR ARRAY BASE PARA MODIFICAR
$param = [
    "idusuario" => $usuario->getIdUsuario(),
    "usnombre" => $datos['usnombre'] ?? $usuario->getNombre(),
    "uspass" => $usuario->getPassword(),
    "usmail" => $datos['usmail'] ?? $usuario->getMail(),
    "usdeshabilitado" => $usuario->getDeshabilitado()
];

if (isset($datos['uspass']) && $datos['uspass'] !== "" && $datos['uspass'] !== "null") {
    $param["uspass"] = password_hash($datos["uspass"], PASSWORD_DEFAULT);
}

// 3) GUARDAR LA MODIFICACIÓN DEL USUARIO
$okUser = $abmUsuario->modificacion($param);

if (!$okUser) {
    echo json_encode(['success' => false, 'msg' => 'No se pudo modificar el usuario']);
    exit;
}

// 4) ACTUALIZAR EL ROL
$idBuscar = [
    'idusuario' => $id
];

$relacionRol = $abmUsuarioRol->buscar(['idusuario' => $id]);
// SI TIENE ROLES → BORRAR TODOS
if ($relacionRol && count($relacionRol) > 0) {
    foreach ($relacionRol as $rol) {
        $abmUsuarioRol->baja([
            'idusuario' => $id,
            'idrol' => $rol->getIdRol()
        ]);
    }
}

// ASIGNAR ROL NUEVO
$abmUsuarioRol->alta([
    'idusuario' => $id,
    'idrol' => $rolNuevo
]);

echo json_encode([
    'success' => true,
    'msg' => 'Usuario modificado correctamente'
]);
exit;

