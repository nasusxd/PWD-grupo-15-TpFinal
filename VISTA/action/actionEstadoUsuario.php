<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$datos = datasubmitted();
$resp = ['success' => false, 'msg' => 'No se pudo realizar la acción.'];

if (isset($datos['idusuario']) && isset($datos['accion'])) {
    $abmUsuario = new ABMUsuario();
    $session = new Session();

    
    if ($datos['idusuario'] == $session->getUsuario()) {
        echo json_encode(['success' => false, 'msg' => 'No puedes deshabilitar tu propio usuario.']);
        exit;
    }

   $listaUsuarios = $abmUsuario->buscar(['idusuario' => $datos['idusuario']]);

    if (count($listaUsuarios) > 0) {
        $usuarioActual = $listaUsuarios[0];

        
        $nuevaFecha = null; 
        
        if ($datos['accion'] == 'deshabilitar') {
            $nuevaFecha = date('Y-m-d H:i:s'); 
        }


        $param = [
            'idusuario' => $datos['idusuario'],
            'usnombre' => $usuarioActual->getNombre(),
            'usmail' => $usuarioActual->getMail(),
            'uspass' => $usuarioActual->getPassword(), 
            'usdeshabilitado' => $nuevaFecha
        ];

       
        if ($abmUsuario->modificacion($param)) {
            $resp['success'] = true;
            $resp['msg'] = "Estado actualizado correctamente.";
        }
    } else {
        $resp['msg'] = "Usuario no encontrado.";
    }
}

echo json_encode($resp);
exit;
?>