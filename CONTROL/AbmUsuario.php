<?php

class ABMUsuario
{

    public function cargarObjeto($param)
    {
        $obj = null;

        if (array_key_exists('idusuario', $param) && array_key_exists('usnombre', $param) && array_key_exists('uspass', $param) && array_key_exists('usmail', $param) && array_key_exists('usdeshabilitado', $param)) {
            $obj = new Usuario();
            $obj->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param)
    {
        $objUsuario = null;
        if (isset($param['idusuario'])) {
            $objUsuario = new Usuario();
            $objUsuario->cargarDatos(['idusuario' => $param['idusuario']]);
        }
        return $objUsuario;
    }

    public function alta($param)
    {
        $resp = false;
        $nuevoUsuario = [
            "idusuario" => null,
            "usnombre" => $param['usnombre'],
            "uspass" => $param['uspass'],
            "usmail" => $param['usmail'],
            "usdeshabilitado" => null
        ];

        $cargarUsuario = $this->cargarObjeto($nuevoUsuario);
        if ($cargarUsuario != null && $cargarUsuario->insertarUsuario()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if (array_key_exists('idusuario', $param)) {
            $objUsuario = $this->cargarObjetoConClave($param);
            if ($objUsuario != null && $objUsuario->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if (array_key_exists('idusuario', $param)) {
            $objUsuario = $this->cargarObjeto($param);
            if ($objUsuario != null && $objUsuario->modificarUsuario($param)) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = "true";
        if ($param != null) {
            if (isset($param['idusuario']))
                $where .= " and idusuario = " . $param['idusuario'];
            if (isset($param['usnombre']))
                $where .= " and usnombre = '" . $param['usnombre'] . "'";
            if (isset($param['usmail']))
                $where .= " and usmail = '" . $param['usmail'] . "'";
            if (isset($param['usdeshabilitado']))
                $where .= " and usdeshabilitado = '" . $param['usdeshabilitado'] . "'";
        }
        $objUsuario = new Usuario();
        $arreglo = $objUsuario->listarUsuarios($where);
        return $arreglo;
    }
    public function modificarUsuarioConRol($datos)
    {
        $resp = ["success" => false, "msg" => "Error al modificar el usuario"];

        if (!isset($datos["idusuario"])) {
            $resp["msg"] = "ID de usuario no recibido";
            return $resp;
        }

        $buscado = $this->buscar(["idusuario" => $datos["idusuario"]]);
        if (empty($buscado)) {
            $resp["msg"] = "Usuario no encontrado";
            return $resp;
        }

        $usuario = $buscado[0];

        // Construir parámetros para modificacion()
        $param = [
            "idusuario" => $usuario->getIdUsuario(),
            "usnombre" => $datos["usnombre"] ?? $usuario->getNombre(),
            "uspass" => $usuario->getPassword(),
            "usmail" => $datos["usmail"] ?? $usuario->getMail(),
            "usdeshabilitado" => $usuario->getDeshabilitado()
        ];

        // Si envió nueva contraseña → hashearla
        if (isset($datos["uspass"]) && $datos["uspass"] != "" && $datos["uspass"] != "null") {
            $param["uspass"] = password_hash($datos["uspass"], PASSWORD_DEFAULT);
        }

        // Guardar usuario
        if (!$this->modificacion($param)) {
            $resp["msg"] = "No se pudo modificar el usuario";
            return $resp;
        }

        // --- Manejo del Rol ---
        $abmUsuarioRol = new ABMUsuarioRol();

        // Obtener roles actuales
        $rolesActuales = $abmUsuarioRol->buscar(["idusuario" => $usuario->getIdUsuario()]);

        // Eliminarlos todos (aunque el diseño deje un solo rol)
        if (!empty($rolesActuales)) {
            foreach ($rolesActuales as $rol) {
                $abmUsuarioRol->baja([
                    "idusuario" => $usuario->getIdUsuario(),
                    "idrol" => $rol->getIdRol()
                ]);
            }
        }

        // Asignar nuevo rol
        if (isset($datos["idrol"])) {
            $abmUsuarioRol->alta([
                "idusuario" => $usuario->getIdUsuario(),
                "idrol" => $datos["idrol"]
            ]);
        }

        return ["success" => true, "msg" => "Usuario modificado correctamente"];
    }
    public function cambiarEstadoUsuario($idUsuario, $accion)
    {

        $lista = $this->buscar(['idusuario' => $idUsuario]);

        if (count($lista) == 0) {
            return false;
        }

        $usuario = $lista[0];

        // Si la acción es deshabilitar → fecha actual
        // Si la acción es habilitar → null
        $nuevaFecha = ($accion == 'deshabilitar') ? date('Y-m-d H:i:s') : null;

        $param = [
            'idusuario' => $usuario->getIdUsuario(),
            'usnombre' => $usuario->getNombre(),
            'usmail' => $usuario->getMail(),
            'uspass' => $usuario->getPassword(),
            'usdeshabilitado' => $nuevaFecha
        ];

        return $this->modificacion($param);
    }
    public function login($mail, $password)
    {
        $lista = $this->buscar(['usmail' => $mail]);

        if (count($lista) != 1) {
            return null;
        }

        $usuario = $lista[0];

        // usuario deshabilitado
        if ($usuario->getDeshabilitado() != null) {
            return null;
        }

        // contraseña incorrecta
        if (!password_verify($password, $usuario->getPassword())) {
            return null;
        }

        return $usuario;
    }


    public function registrarUsuario($datos)
    {

        $abmUsuarioRol = new ABMUsuarioRol();

        // 1) Validar duplicados
        $existe = $this->buscar(["usnombre" => $datos["usnombre"]]);
        $existeMail = $this->buscar(["usmail" => $datos["usmail"]]);

        if (!empty($existe) || !empty($existeMail)) {
            return [
                "success" => false,
                "msg" => "El nombre de usuario o email ya están registrados."
            ];
        }

        // 2) Crear usuario
        if (!$this->alta($datos)) {
            return [
                "success" => false,
                "msg" => "No se pudo crear el usuario."
            ];
        }

        // 3) Recuperar el usuario recién creado
        $nuevo = $this->buscar(["usnombre" => $datos["usnombre"]])[0];
        $idUsuario = $nuevo->getIdUsuario();

        // 4) Rol por defecto o enviado
        $idRol = $datos["idrol"] ?? 1;

        if (!$abmUsuarioRol->alta(["idusuario" => $idUsuario, "idrol" => $idRol])) {
            return [
                "success" => false,
                "msg" => "Usuario creado, pero falló la asignación del rol."
            ];
        }

        return [
            "success" => true,
            "msg" => "Usuario creado y rol asignado correctamente."
        ];
    }
    public function altaCompleta($datos)
    {
        $resp = ["success" => false, "msg" => ""];

        // Validar email ya registrado
        $existe = $this->buscar(['usmail' => $datos['usmail']]);
        if (count($existe) > 0) {
            $resp["msg"] = "El email ingresado ya está registrado";
            return $resp;
        }

        // Alta de usuario
        if ($this->alta($datos)) {

            // Obtener ID del usuario recién creado
            $nuevo = $this->buscar(['usmail' => $datos['usmail']]);
            if (empty($nuevo)) {
                $resp["msg"] = "Error al recuperar el ID del usuario";
                return $resp;
            }

            $idUsuario = $nuevo[0]->getIdUsuario();

            // Asignar rol por defecto (1 = Cliente)
            $abmUsuarioRol = new ABMUsuarioRol();
            $paramRol = [
                "idusuario" => $idUsuario,
                "idrol" => 1
            ];

            if ($abmUsuarioRol->alta($paramRol)) {
                return ["success" => true, "msg" => "OK"];
            }

            $resp["msg"] = "Usuario creado, pero no se pudo asignar el rol";
            return $resp;
        }

        $resp["msg"] = "No se pudo crear el usuario";
        return $resp;
    }


    public function getDatosCompletos($id)
    {
        $user = $this->buscar(['idusuario' => $id]);
        return $user ? $user[0] : null;
    }

    public function actualizarNombre($id, $nombre)
    {
        $user = $this->getDatosCompletos($id);
        if (!$user) return false;

        $param = [
            "idusuario" => $id,
            "usnombre" => $nombre,
            "uspass" => $user->getPassword(),
            "usmail" => $user->getMail(),
            "usdeshabilitado" => $user->getDeshabilitado()
        ];

        return $this->modificacion($param);
    }

    public function actualizarEmail($id, $email)
    {
        if (count($this->buscar(['usmail' => $email])) > 0) {
            return "email_ocupado";
        }

        $user = $this->getDatosCompletos($id);
        if (!$user) return false;

        $param = [
            "idusuario" => $id,
            "usnombre" => $user->getNombre(),
            "uspass" => $user->getPassword(),
            "usmail" => $email,
            "usdeshabilitado" => $user->getDeshabilitado()
        ];

        return $this->modificacion($param);
    }

    public function actualizarPassword($id, $passPlano)
    {
        $user = $this->getDatosCompletos($id);
        if (!$user) return false;

        $param = [
            "idusuario" => $id,
            "usnombre" => $user->getNombre(),
            "uspass" => password_hash($passPlano, PASSWORD_DEFAULT),
            "usmail" => $user->getMail(),
            "usdeshabilitado" => $user->getDeshabilitado()
        ];

        return $this->modificacion($param);
    }
}
