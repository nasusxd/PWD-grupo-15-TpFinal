<?php
class ABMCompraEstadoTipo {

    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idcompraestadotipo', $param) && array_key_exists('cetdescripcion', $param) && array_key_exists('cetdetalle', $param)) {
            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idcompraestadotipo'])) {
            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->cargarDatos(['idcompraestadotipo' => $param['idcompraestadotipo']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objCompraEstadoTipo = $this->cargarObjeto($param);
        if ($objCompraEstadoTipo != null && $objCompraEstadoTipo->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idcompraestadotipo', $param)) {
            $objCompraEstadoTipo = $this->cargarObjetoConClave($param);
            if ($objCompraEstadoTipo != null && $objCompraEstadoTipo->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idcompraestadotipo', $param)) {
            $objCompraEstadoTipo = $this->cargarObjeto($param);
            if ($objCompraEstadoTipo != null && $objCompraEstadoTipo->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idcompraestadotipo']))
                $where .= " and idcompraestadotipo = " . $param['idcompraestadotipo'];
            if (isset($param['cetdescripcion']))
                $where .= " and cetdescripcion = '" . $param['cetdescripcion'] . "'";
            if (isset($param['cetdetalle']))
                $where .= " and cetdetalle = '" . $param['cetdetalle'] . "'";
        }
        $objCompraEstadoTipo = new CompraEstadoTipo();
        $arreglo = $objCompraEstadoTipo->listar($where);
        return $arreglo;
    }
}