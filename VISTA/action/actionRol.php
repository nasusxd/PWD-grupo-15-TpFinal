<?php
include_once "../../configuracion.php";

$datos = dataSubmitted();
$resp = false;
$mensaje = "";

$objAbmRol = new ABMRol();

if (isset($datos['accion'])) {

    if ($datos['accion'] == 'nuevo') {

        print_r($datos);
        $paramRol  = [
            "idrol" => null,
            "rodescripcion" => $datos['rodescripcion']
        ];
        if ($objAbmRol->alta($paramRol)) {
            $resp = true;
            $mensaje = "Rol creado correctamente.";
        } else {
            $mensaje = "Error al crear el rol.";
        }
    } elseif ($datos['accion'] == 'borrar') {

        if (isset($datos['idrol'])) {
            $idRol = $datos['idrol'];

            if ($idRol == 1 || $idRol == 2) {
                $mensaje = "ERROR: No se pueden eliminar los roles de Sistema (Admin y Cliente).";
            } else {

                $objAbmMenuRol = new ABMMenuRol();
                $permisos = $objAbmMenuRol->buscar(['idrol' => $idRol]);

                foreach ($permisos as $permiso) {
                    $permiso->eliminar();
                }


                if ($objAbmRol->baja(['idrol' => $idRol])) {
                    $resp = true;
                    $mensaje = "Rol eliminado correctamente.";
                } else {
                    $mensaje = "No se pudo eliminar el rol.";
                }
            }
        }
    }
}

echo json_encode(['respuesta' => $resp, 'mensaje' => $mensaje]);
