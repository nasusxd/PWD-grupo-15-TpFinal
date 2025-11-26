<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

$abmMenuRol = new ABMMenuRol();
$abmMenu    = new ABMMenu();

// ======================================================
// 1) GUARDAR ROLES DE MENÚ (HIJO + PADRE)
// ======================================================
if (isset($_POST['idmenu']) && isset($_POST['roles'])) {

    $idMenu = intval($_POST['idmenu']);
    $roles  = $_POST['roles'];

    $resp = $abmMenuRol->guardarRolesMenu($idMenu, $roles);

    echo json_encode($resp);
    exit;
}


// ======================================================
// 2) HABILITAR / DESHABILITAR MENÚ
// ======================================================
if (isset($_POST['idMenu']) && isset($_POST['deshabilitado'])) {

    $resp = $abmMenu->cambiarEstadoMenu($_POST);

    echo json_encode($resp);
    exit;
}
