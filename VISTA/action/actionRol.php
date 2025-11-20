<?php
include_once "../../configuracion.php";

$datos = dataSubmitted();
$objAbmRol = new ABMRol();

$resp = false;
$mensaje = "";
$response = [];

if (isset($datos['accion'])) {

    /* ───────────────────────────────────────────
       CREAR ROL
    ─────────────────────────────────────────── */
    if ($datos['accion'] == 'nuevo') {

        $paramRol  = [
            "idrol" => null,
            "rodescripcion" => $datos['rodescripcion']
        ];

        if ($objAbmRol->alta($paramRol)) {

            // Traer el rol recién creado
            $nuevoRol = $objAbmRol->buscar([
                'rodescripcion' => $datos['rodescripcion']
            ])[0];

            $response = [
                "success" => true,
                "mensaje" => "Rol creado correctamente.",
                "id" => $nuevoRol->getIdRol(),
                "descripcion" => $nuevoRol->getRoDescripcion()
            ];
        } else {
            $response = [
                "success" => false,
                "mensaje" => "Error al crear el rol."
            ];
        }
    }

    /* ──────────────────────────────
   BORRAR ROL
──────────────────────────────── */ elseif ($datos['accion'] == 'borrar') {

        $idRol = $datos['idrol'];

        // No borrar roles protegidos
        if ($idRol == 1 || $idRol == 2) {

            $response = [
                "success" => false,
                "mensaje" => "No se pueden eliminar los roles del sistema."
            ];
        } else {

            // ✨ NUEVO: ver si hay usuarios con este rol
            $objUsuarioRol = new UsuarioRol();
            $asignados = $objUsuarioRol->listar("idrol = " . $idRol);

            if (!empty($asignados)) {
                $response = [
                    "success" => false,
                    "mensaje" => "No se puede eliminar el rol porque está asignado a uno o más usuarios."
                ];
            } else {

                // Eliminar permisos del rol
                $objAbmMenuRol = new ABMMenuRol();
                $permisos = $objAbmMenuRol->buscar(['idrol' => $idRol]);
                foreach ($permisos as $permiso) {
                    $permiso->eliminar();
                }

                // Eliminar rol
                if ($objAbmRol->baja(['idrol' => $idRol])) {
                    $response = [
                        "success" => true,
                        "mensaje" => "Rol eliminado correctamente."
                    ];
                } else {
                    $response = [
                        "success" => false,
                        "mensaje" => "No se pudo eliminar el rol."
                    ];
                }
            }
        }
    }
}

echo json_encode($response);
