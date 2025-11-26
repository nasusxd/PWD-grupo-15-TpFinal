<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

if (!isset($_POST['idmenu'])) {
    echo json_encode(["success" => false, "message" => "ID menú no recibido"]);
    exit;
}

$idMenu = intval($_POST['idmenu']);

$abmMenuRol = new ABMMenuRol();
$roles = $abmMenuRol->obtenerRolesDeMenu($idMenu);

echo json_encode([
    "success" => true,
    "roles" => $roles
]);
?>
