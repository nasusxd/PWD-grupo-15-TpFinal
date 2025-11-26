<?php
include_once "../../configuracion.php";

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "ID no recibido"]);
    exit;
}

$objProducto = new ABMProducto();

if ($objProducto->bajaLogica($id)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error al desactivar"]);
}
