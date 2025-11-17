<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = datasubmitted();
$resp = false;
$mensaje = "No se pudo crear el usuario.";

$abmUsuario = new ABMUsuario();
$abmUsuarioRol = new ABMUsuarioRol();


$existe = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);
$existeMail = $abmUsuario->buscar(['usmail' => $datos['usmail']]);

if (count($existe) > 0 || count($existeMail) > 0) {
    echo json_encode(['success' => false, 'msg' => 'El nombre de usuario o email ya están registrados.']);
    exit;
}


if ($abmUsuario->alta($datos)) {
    
   
    $nuevoUsuarioArr = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);
    
    if (!empty($nuevoUsuarioArr)) {
        $objNuevoUsuario = $nuevoUsuarioArr[0];
        $idNuevo = $objNuevoUsuario->getIdUsuario();
        
       
        $idRol = isset($datos['idrol']) ? $datos['idrol'] : 1;
        
        $paramRol = [
            'idusuario' => $idNuevo,
            'idrol' => $idRol
        ];
        
        if ($abmUsuarioRol->alta($paramRol)) {
            $resp = true;
            $mensaje = "Usuario creado y rol asignado correctamente.";
        } else {
            $mensaje = "Usuario creado, pero falló la asignación de rol.";
        }
    } else {
        $mensaje = "Error al recuperar el ID del nuevo usuario.";
    }
}

echo json_encode(['success' => $resp, 'msg' => $mensaje]);
exit;
?>