<?php
class ABMMenu {

    public function cargarObjeto($param) {
        $obj = null;

    if (array_key_exists('idmenu', $param) && array_key_exists('menombre', $param) && array_key_exists('medescripcion', $param) && array_key_exists('idpadre', $param) && array_key_exists('medeshabilitado', $param)) {
            $objMenu = new Menu();
            $objMenu->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idmenu'])) {
            $objMenu = new Menu();
            $objMenu->cargarDatos(['idmenu' => $param['idmenu']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objMenu = $this->cargarObjeto($param);
        if ($objMenu != null && $objMenu->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idmenu', $param)) {
            $objMenu = $this->cargarObjetoConClave($param);
            if ($objMenu != null && $objMenu->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idmenu', $param)) {
            $objMenu = $this->cargarObjeto($param);
            if ($objMenu != null && $objMenu->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = 'true';
        if ($param != null) {
            if (array_key_exists('idmenu', $param)) 
                $where .= " AND idmenu = " . $param['idmenu'];
            if (array_key_exists('menombre', $param))
                $where .= " AND menombre = '" . $param['menombre'] . "'";
            if (array_key_exists('medescripcion', $param))
                $where .= " AND medescripcion = '" . $param['medescripcion'] . "'";
            if (array_key_exists('idpadre', $param)) {
                if (is_null($param['idpadre'])) {
                     $where .= " AND idpadre IS NULL";
                } else {
                    $where .= " AND idpadre = " . $param['idpadre'];
                }
            }
            if (array_key_exists('medeshabilitado', $param))
                $where .= " AND medeshabilitado = '" . $param['medeshabilitado'] . "'";
            $objMenu = new Menu();
            $arreglo = $objMenu->listar($where);
            return $arreglo;
        }
    }
}