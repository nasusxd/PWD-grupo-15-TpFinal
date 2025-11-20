<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";
$datos = datasubmitted();
$resp = false;
$mensaje = "No se pudo crear el menú.";

// ABMs necesarios
$abmMenu = new ABMMenu();
$abmMenuRol = new ABMMenuRol();


if (
    !isset($datos["pronombre"]) ||
    !isset($datos["prodetalle"]) ||
    !isset($datos["medeshabilitado"])
) {
    echo json_encode(["success" => false, "msg" => "Faltan datos obligatorios."]);
    exit;
}

$existe = $abmMenu->buscar(['menombre' => $datos['pronombre']]);
if (count($existe) > 0) {
    echo json_encode(["success" => false, "msg" => "Ya existe un menú con ese nombre."]);
    exit;
}

// CREAR MENÚ

$paramMenu = [
    "idmenu" => null,
    "menombre" => $datos["pronombre"],
    "medescripcion" => $datos["prodetalle"],
    "idpadre" => null,  
    "medeshabilitado" => $datos["medeshabilitado"],
];

if ($abmMenu->alta($paramMenu)) {

    // Recuperar el menú recién creado
    $nuevoMenuArr = $abmMenu->buscar(['menombre' => $datos['pronombre']]);

    if (!empty($nuevoMenuArr)) {
        $objMenu = $nuevoMenuArr[0];
        $idMenu = $objMenu->getIdMenu();
        
        // ASIGNAR ROLES

        if (isset($datos["menuRoles"]) && is_array($datos["menuRoles"])) {

            foreach ($datos["menuRoles"] as $idRol) {
                $paramMenuRol = [
                    "idmenu" => $idMenu,
                    "idrol" => $idRol
                ];
                $abmMenuRol->alta($paramMenuRol);
            }
        }

        $resp = true;
        $mensaje = "Menú creado correctamente.";
    } else {
        $mensaje = "Error al recuperar el menú recién creado.";
    }

} else {
    $mensaje = "Falló la creación del menú.";
}

echo json_encode(['success' => $resp, 'msg' => $mensaje]);
exit;
