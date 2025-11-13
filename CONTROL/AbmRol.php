<?php 

class ABMRol {

    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idrol', $param) && array_key_exists('rodescripcion', $param)) {
            $objRol = new Rol();
            $objRol->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idrol'])) {
            $objRol = new Rol();
            $objRol->cargarDatos(['idrol' => $param['idrol']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objRol = $this->cargarObjeto($param);
        if ($objRol != null && $objRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idrol', $param)) {
            $objRol = $this->cargarObjetoConClave($param);
            if ($objRol != null && $objRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idrol', $param)) {
            $objRol = $this->cargarObjeto($param);
            if ($objRol != null && $objRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idrol']))
                $where .= " and idrol = " . $param['idrol'];
            if (isset($param['rodescripcion']))
                $where .= " and rodescripcion = '" . $param['rodescripcion'] . "'";
        }
        $objRol = new Rol();
        $arreglo = $objRol->listar($where);
        return $arreglo;
    }
}    