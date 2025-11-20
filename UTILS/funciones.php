<?php 
include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '../../configuracion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function datasubmitted() {
    $_AAux= array();
    if (!empty($_POST))
        $_AAux =$_POST;
        else
            if(!empty($_GET)) {
                $_AAux =$_GET;
            }
        if (count($_AAux)){
            foreach ($_AAux as $indice => $valor) {
                if ($valor=="")
                    $_AAux[$indice] = 'null' ;
            }
        }
        return $_AAux;
        
}

// Autoload automático de clases
spl_autoload_register(function ($clase) {
    // Definimos los directorios donde buscar clases
    $directorios = [
        $GLOBALS['ROOT'] . 'CONTROL/',
        $GLOBALS['ROOT'] . 'MODELO/',
        $GLOBALS['ROOT'] . 'MODELO/conector/',
        $GLOBALS['ROOT'] . 'UTILS/',
    ];

    // Recorremos los directorios buscando el archivo que coincida con el nombre de la clase
    foreach ($directorios as $dir) {
        $archivo = $dir . $clase . '.php';
        if (file_exists($archivo)) {
            require_once($archivo);
            return;
        }
    }
});

function enviarCorreo($correoCliente, $subject, $nombre, $mensajeCliente) {
    $res = false;
    $mail = new PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP a utilizar. Por ej. smtp.elserver.com
        $mail->SMTPAuth = true;
        $mail->Username = 'grupo15pwd@gmail.com'; // correo
        $mail->Password = 'dldrmbwtojfpaats'; // Contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //remitente
        $mail->setFrom('grupo15pwd@gmail.com', 'Soporte Pelunco');

        //destinatario
        $mail->addAddress($correoCliente);

        //contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
        <p>Hola <strong>$nombre</strong>,</p>
        <p>Gracias por contactarte con nosotros. Este fue tu mensaje:</p>
        <blockquote>$mensajeCliente</blockquote>
        <p>Pronto nos comunicaremos con vos.</p>
        <p>Saludos,<br>El equipo de soporte Pelunco</p>
        ";

        //alternativa 
        $mail->AltBody = "Hola $nombre, gracias por contactarte. Tu mensaje fue: $mensajeCliente";
        
        //lo mando
        $mail->send();
        $res = true;
    } catch (Exception $e) {
        $res = "Error al enviar el correo: " . $e->getMessage();
    }
    return $res;
}

function enviarCorreoResumen($correoCliente, $carrito) {
    $res = false;
    $mail = new PHPMailer();
    $objabmProducto = new ABMProducto();
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP a utilizar. Por ej. smtp.elserver.com
        $mail->SMTPAuth = true;
        $mail->Username = 'grupo15pwd@gmail.com'; // correo
        $mail->Password = 'dldrmbwtojfpaats'; // Contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //remitente
        $mail->setFrom('grupo15pwd@gmail.com', 'Soporte Pelunco');

        //destinatario
        $mail->addAddress($correoCliente);

        //contenido del correo
        $mail->isHTML(true);
        $body = "
        <p>Hola <strong>$correoCliente</strong>,</p>
        <p>Gracias por tu compra. Aca esta tu resumen:</p>
        <ul>";
        foreach ($carrito as $idProducto => $cantidad) {
            $producto = $objabmProducto->buscar(['idproducto' => $idProducto]);
            if (count($producto) > 0) {
                $producto = $producto[0];
            }
            $body .= "
            <li>
                <strong>" . $producto->getNombre() . "</strong><br>
                Detalle: " . $producto->getDetalle() . "<br>
                Precio: $" . $producto->getPrecio() . "<br>
                Cantidad: $cantidad
            </li><br>";
        }
        $body .= "</ul>
        <p>Saludos,<br>El equipo de Pelunco</p>";

        $mail->Body = $body;
        //lo mando
        $mail->send();
        $res = true;
    } catch (Exception $e) {
        $res = "Error al enviar el correo: " . $e->getMessage();
    }
    return $res;
}

function enviarCorreoCambioEstado($correoCliente, $nombreCliente, $idCompra, $estadoNuevo) {
    $res = false;
    $mail = new PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'grupo15pwd@gmail.com';
        $mail->Password = 'dldrmbwtojfpaats';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente
        $mail->setFrom('grupo15pwd@gmail.com', 'Pelunco - Actualizacion de compra');

        // Destinatario
        $mail->addAddress($correoCliente);

        // Asunto
        $mail->isHTML(true);
        $mail->Subject = "Actualizacion en tu compra #$idCompra";

        // Cuerpo del mensaje
        $mail->Body = "
            <p>Hola <strong>$nombreCliente</strong>,</p>
            <p>Queriamos informarte que el estado de tu compra <strong>#$idCompra</strong> ha cambiado.</p>

            <p><strong>Nuevo estado:</strong> <span style='color:#1a73e8'>$estadoNuevo</span></p>

            <p>Gracias por confiar en Pelunco.</p>
            <p>Saludos,<br>Equipo Pelunco</p>
        ";

        // Alternativa en texto plano
        $mail->AltBody = "Hola $nombreCliente, tu compra #$idCompra cambio al estado: $estadoNuevo.";

        // Enviar
        $mail->send();
        $res = true;

    } catch (Exception $e) {
        $res = "Error al enviar correo: " . $e->getMessage();
    }

    return $res;
}


?>