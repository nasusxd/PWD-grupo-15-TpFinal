<?php

class ABMUsuarioRol {

    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idusuario', $param) && array_key_exists('idrol', $param)) {
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;

        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->cargarDatos(['idusuario' => $param['idusuario'], 'idrol' => $param['idrol']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objUsuarioRol = $this->cargarObjeto($param);
        if ($objUsuarioRol != null && $objUsuarioRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idusuario', $param) && array_key_exists('idrol', $param)) {
            $objUsuarioRol = $this->cargarObjetoConClave($param);
            if ($objUsuarioRol != null && $objUsuarioRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idusuario', $param) && array_key_exists('idrol', $param)) {
            $objUsuarioRol = $this->cargarObjeto($param);
            if ($objUsuarioRol != null && $objUsuarioRol->modificar()) {
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
            if (isset($param['idrol']))
                $where .= " and idrol = " . $param['idrol'];
        }
        $objUsuarioRol = new UsuarioRol();
        $arreglo = $objUsuarioRol->listar($where);
        return $arreglo;
    }
}