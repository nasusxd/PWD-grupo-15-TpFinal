<?php
include_once "../../configuracion.php";

$session = new Session();

if (!$session->validar()) {
    echo json_encode(["success" => false, "error" => "Sesión expirada"]);
    exit;
}

$idUsuario = $session->getUsuario();
$accion = $_POST["accion"];
$abm = new ABMUsuario();

switch ($accion) {

    case "nombre":
        $res = $abm->actualizarNombre($idUsuario, trim($_POST["usnombre"]));
        break;

    case "email":
        $res = $abm->actualizarEmail($idUsuario, trim($_POST["usmail"]));
        if ($res === "email_ocupado") {
            echo json_encode(["success" => false, "mensaje" => "El email ya está registrado"]);
            exit;
        }
        break;

    case "pass":
        $res = $abm->actualizarPassword($idUsuario, $_POST["pass1"]);
        break;

    default:
        echo json_encode(["success" => false, "mensaje" => "Acción inválida"]);
        exit;
}

echo json_encode([
    "success" => $res ? true : false,
    "mensaje" => $res ? "Datos actualizados" : "Error al guardar"
]);
