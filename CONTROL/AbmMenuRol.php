<?php

class ABMMenuRol {
    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
            $objMenuRol = new MenuRol();
            $objMenuRol->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;

        if (isset($param['idmenu']) && isset($param['idrol'])) {
            $objMenuRol = new MenuRol();
            $objMenuRol->cargarDatos(['idmenu' => $param['idmenu'], 'idrol' => $param['idrol']]);
        }
        return $obj;
    }
    
    public function alta($param) {
        $resp = false;
        $objMenuRol = $this->cargarObjeto($param);
        if ($objMenuRol != null && $objMenuRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
            $objMenuRol = $this->cargarObjetoConClave($param);
            if ($objMenuRol != null && $objMenuRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
            $objMenuRol = $this->cargarObjeto($param);
            if ($objMenuRol != null && $objMenuRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idmenu']))
                $where .= " and idmenu = " . $param['idmenu'];
            if (isset($param['idrol']))
                $where .= " and idrol = " . $param['idrol'];
        }
        $objMenuRol = new MenuRol();
        $arreglo = $objMenuRol->listar($where);
        return $arreglo;
    }
}