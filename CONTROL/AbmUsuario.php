<?php

class ABMUsuario {
    public function actualizarContrasena($idUsuario, $datos) {

    }

    public function actualizarPerfil($idUsuario, $datos) {

    }

    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idusuario', $param) && array_key_exists('usnombre', $param) && array_key_exists('uspass', $param) && array_key_exists('usmail', $param) && array_key_exists('usdeshabilitado', $param)) {
            $obj = new Usuario();
            $obj->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idusuario'])) {
            $objUsuario = new Usuario();
            $objUsuario->cargarDatos(['idusuario' => $param['idusuario']]);
        }
        return $obj;
    }

    public function alta($param) {
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

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idusuario', $param)) {
            $objUsuario = $this->cargarObjetoConClave($param);
            if ($objUsuario != null && $objUsuario->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idusuario', $param)) {
            $objUsuario = $this->cargarObjeto($param);
            if ($objUsuario != null && $objUsuario->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
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
}