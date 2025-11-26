<?php

class ABMCorreo {

    /**
     * Envía una consulta por correo
     * No usa base de datos.
     */
    public function enviarConsulta($datos)
    {
        // Validaciones
        if (
            empty($datos['correo']) ||
            empty($datos['asunto']) ||
            empty($datos['nombre']) ||
            empty($datos['mensaje'])
        ) {
            return [
                "success" => false,
                "msg" => "Faltan datos para enviar la consulta."
            ];
        }

        // Llama a tu función real que ya tenés
        $resultado = enviarCorreo(
            $datos['correo'],
            $datos['asunto'],
            $datos['nombre'],
            $datos['mensaje']
        );

        if ($resultado === true) {
            return [
                "success" => true,
                "msg" => "Se recibió tu consulta."
            ];
        } else {
            return [
                "success" => false,
                "msg" => "Error al enviar el correo: " . $resultado
            ];
        }
    }
}
