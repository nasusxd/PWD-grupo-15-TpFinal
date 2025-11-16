<?php
include_once "../../configuracion.php";

$session = new Session();


if (!$session->validar()) {
    echo json_encode(["success" => false, "error" => "Tu sesión ha expirado. Por favor, inicia sesión de nuevo."]);
    exit;
}


$usuario = $session->getUsuario();

$accion = $_POST["accion"];
$abm = new AbmUsuario();
$resultado = false;


$datosActuales = $abm->buscar(['idusuario' => $usuario]);
if (!$datosActuales) {
    echo json_encode(["success" => false, "error" => "Usuario no encontrado"]);
    exit;
}

$datosActuales = $datosActuales[0];

//este param me estaba arruinando la vida
$param = [
    "idusuario" => $usuario,
    "usnombre" => $datosActuales->getNombre(),      
    "uspass" => $datosActuales->getPassword(),    
    "usmail" => $datosActuales->getMail(),        
    "usdeshabilitado" => $datosActuales->getDeshabilitado() 
];


if ($accion == "nombre") {
    $param["usnombre"] = trim($_POST["usnombre"]);
    $resultado = $abm->modificacion($param);
}


if ($accion == "email") {
    $param["usmail"] = trim($_POST["usmail"]);
    $resultado = $abm->modificacion($param);
}


if ($accion == "pass") {
    $p1 = $_POST["pass1"];
    
    $param["uspass"] = password_hash($p1, PASSWORD_DEFAULT); //hasheamos directamente aca
    
    $resultado = $abm->modificacion($param);
}
echo json_encode([
    "success" => $resultado,
    "mensaje" => $resultado ? "Datos actualizados" : "Error al guardar"
]);
