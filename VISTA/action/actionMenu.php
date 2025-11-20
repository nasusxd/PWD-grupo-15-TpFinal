<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

$abmMenuRol = new ABMMenuRol();
$abmMenu = new ABMMenu();


// ======================================================
// 1) GUARDAR ROLES DE MENÚ  (HIJO + PADRE)
// ======================================================
if (isset($_POST['idmenu']) && isset($_POST['roles'])) {

    $idMenu = intval($_POST['idmenu']);
    $rolesSeleccionados = $_POST['roles'];

    // ---------- BORRAR ROLES ACTUALES DEL MENÚ ----------
    $actuales = $abmMenuRol->buscar(["idmenu" => $idMenu]);
    foreach ($actuales as $mr) {
        $mr->eliminar();
    }

    // ---------- INSERTAR NUEVOS ROLES DEL MENÚ ----------
    foreach ($rolesSeleccionados as $idRol) {
        $abmMenuRol->alta([
            "idmenu" => $idMenu,
            "idrol"  => intval($idRol)
        ]);
    }

    /* ============================================================
   🔵 NUEVO: AÑADIR AL PADRE SOLO LOS ROLES NUEVOS, SIN BORRARLE NADA
============================================================ */
    $menuData = $abmMenu->buscar(['idmenu' => $idMenu]);

    if (!empty($menuData)) {

        $idPadre = $menuData[0]->getIdPadre();

        if ($idPadre != 0) {

            // Obtener roles actuales del padre
            $rolesPadreActuales = $abmMenuRol->buscar(["idmenu" => $idPadre]);
            $rolesPadreIds = array_map(fn($r) => $r->getIdRol(), $rolesPadreActuales);

            // Insertar únicamente los roles que el padre NO tenga
            foreach ($rolesSeleccionados as $idRol) {

                if (!in_array($idRol, $rolesPadreIds)) {

                    $abmMenuRol->alta([
                        "idmenu" => $idPadre,
                        "idrol"  => intval($idRol)
                    ]);
                }
            }
        }
    }


    echo json_encode(["success" => true, "msg" => "Roles actualizados"]);
    exit;
}



// ======================================================
// 2) HABILITAR / DESHABILITAR MENÚ
// ======================================================
if (isset($_POST['idMenu']) && isset($_POST['deshabilitado'])) {

    $idMenu = intval($_POST['idMenu']);
    $nuevoEstado = intval($_POST['deshabilitado']);

    if ($abmMenu->cambiarEstado($idMenu, $nuevoEstado)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "msg" => "No se pudo actualizar el estado"]);
    }

    exit;
}