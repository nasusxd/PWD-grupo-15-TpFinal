<?php
include_once "../../configuracion.php";

$session = new Session();
$usuario = $session->getUsuario();

$accion = $_POST["accion"];  //veo q form vino

$abm = new AbmUsuario();

// Traigo datos actuales de BD
$datosActuales = $abm->buscar(['idusuario' => $usuario['idusuario']]);
if (!$datosActuales) {
    echo json_encode(["success" => false, "error" => "Usuario no encontrado"]);
    exit;
}

$datosActuales = $datosActuales[0]; // objeto usuario

// Preparo el array base para modificación
$param = [
    "idusuario" => $usuario["idusuario"],
    "usnombre" => $datosActuales->getUsNombre(),
    "uspass" => $datosActuales->getUsPass(),
    "usmail" => $datosActuales->getUsMail(),
    "usdeshabilitado" => $datosActuales->getUsDeshabilitado()
];

// -------------------------------
//       ACTUALIZAR NOMBRE
// -------------------------------
if ($accion == "nombre") {
    $nuevo = trim($_POST["usnombre"]);
    $param["usnombre"] = $nuevo;
    $resultado = $abm->modificacion($param);
}

// -------------------------------
//         ACTUALIZAR EMAIL
// -------------------------------
if ($accion == "email") {
    $nuevo = trim($_POST["usmail"]);
    $param["usmail"] = $nuevo;
    $resultado = $abm->modificacion($param);
}

// -------------------------------
//        ACTUALIZAR PASS
// -------------------------------
if ($accion == "pass") {

    $p1 = $_POST["pass1"];
    $p2 = $_POST["pass2"];

    if ($p1 !== $p2) {
        echo json_encode(["success" => false, "error" => "Las contraseñas no coinciden"]);
        exit;
    }

    // ⚠ importante: si usas hash, hacelo acá
    $param["uspass"] = $p1;

    $resultado = $abm->modificacion($param);
}

// -------------------------------
//     RESPUESTA JSON
// -------------------------------
echo json_encode([
    "success" => $resultado,
    "mensaje" => $resultado ? "Datos actualizados" : "Error al guardar"
]);
