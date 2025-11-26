<?php

class ABMCorreo {

    /**
     * Envía una consulta por correo
     * No usa base de datos.
     */
    public function enviarConsulta($datos)
    {
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
