<?php
include_once "../../configuracion.php";
header('Content-Type: application/json');

if (isset($_POST['idMenu']) && isset($_POST['deshabilitado'])) {
    $idMenu = intval($_POST['idMenu']);
    $nuevoEstado = intval($_POST['deshabilitado']);

    $abmMenu = new ABMMenu();
    // Buscar hijos habilitados
    $hijosHabilitados = $abmMenu->buscar([
    'idpadre' => $idMenu,
    'medeshabilitado' => 0 // 0 = habilitado
]);

if (!empty($hijosHabilitados) && $nuevoEstado == 1) { // intentar deshabilitar
    echo json_encode([
        'success' => false,
        'message' => ''
    ]);
    exit;
}

    // Usando tu método cambiarEstado directamente
    if ($abmMenu->cambiarEstado($idMenu, $nuevoEstado)) {
        echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}