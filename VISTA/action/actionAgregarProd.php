<?php
header('Content-Type: application/json'); 
include_once "../../configuracion.php";

$datos = datasubmitted();
$response = ["success" => false, "msg" => "Error en el action."]; 
$abmProducto = new ABMProducto();

//para la img
if (isset($_FILES['proimagen']) && $_FILES['proimagen']['error'] === 0) {
    $nombreArchivo = $_FILES['proimagen']['name']; //nombre original del archivo
    $tmpArchivo = $_FILES['proimagen']['tmp_name']; //ubicacion TEMPORAL

    $rutaDestino = "../../uploads/" . $nombreArchivo; //ruta donde se GUARDA

    if (!move_uploaded_file($tmpArchivo, $rutaDestino)) {
        $response = ['success'=>false, 'message'=>"Error al subir la imagen."];
    } else {
        $datos['proimagen'] = $nombreArchivo;
        $cargarProducto = $abmProducto->alta($datos);
        if ($cargarProducto) {
            $response = ['success'=>true, 'message'=>"Producto cargado correctamente."];
        } else {
            $response = ['success'=>false, 'message'=>"Error al guardar el producto."];
        }
    }
} else {
    $response =['success'=>false, 'message'=>"No se selecciono la imagen."];
}

echo json_encode($response);
exit;
