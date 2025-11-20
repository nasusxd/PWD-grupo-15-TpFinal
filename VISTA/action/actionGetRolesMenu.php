<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

if (!isset($_POST['idmenu'])) {
    echo json_encode(["success" => false, "message" => "ID menú no recibido"]);
    exit;
}

$idMenu = intval($_POST['idmenu']);

$abmMenuRol = new ABMMenuRol();
$rolesAsignados = $abmMenuRol->buscar(["idmenu" => $idMenu]);

$idsRoles = [];
foreach ($rolesAsignados as $mr) {
    $idsRoles[] = $mr->getIdRol();
}

echo json_encode([
    "success" => true,
    "roles" => $idsRoles
]);
