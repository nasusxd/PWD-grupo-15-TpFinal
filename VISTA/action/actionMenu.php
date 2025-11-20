<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

$abmMenuRol = new ABMMenuRol();
$abmMenu = new ABMMenu();


// ======================================================
// 1) GUARDAR ROLES DE MENÚ
// ======================================================
if (isset($_POST['idmenu']) && isset($_POST['roles'])) {

    $idMenu = intval($_POST['idmenu']);
    $rolesSeleccionados = $_POST['roles'];

    // Borrar todos los roles actuales
    $actuales = $abmMenuRol->buscar(["idmenu" => $idMenu]);
    foreach ($actuales as $mr) {
        $mr->eliminar();
    }

    // Insertar los nuevos
    foreach ($rolesSeleccionados as $idRol) {
        $abmMenuRol->alta([
            "idmenu" => $idMenu,
            "idrol" => intval($idRol)
        ]);
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

    // Ejecutar cambio de estado
    if ($abmMenu->cambiarEstado($idMenu, $nuevoEstado)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "msg" => "No se pudo actualizar el estado"]);
    }

    exit;
}


// ======================================================
echo json_encode(["success" => false, "msg" => "Acción inválida"]);
