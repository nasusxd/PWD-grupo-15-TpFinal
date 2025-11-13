<?php 

class ABMCompraEstado {

    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idcompraestado', $param) && array_key_exists('idcompra', $param) && array_key_exists('idcompraestadotipo', $param) && array_key_exists('cefechaini', $param) && array_key_exists('cefechafin', $param)) {
            $objCompraEstado = new CompraEstado();
            $objCompraEstado->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idcompraestado'])) {
            $objCompraEstado = new CompraEstado();
            $objCompraEstado->cargarDatos(['idcompraestado' => $param['idcompraestado']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objCompraEstado = $this->cargarObjeto($param);
        if ($objCompraEstado != null && $objCompraEstado->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idcompraestado', $param)) {
            $objCompraEstado = $this->cargarObjetoConClave($param);
            if ($objCompraEstado != null && $objCompraEstado->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idcompraestado', $param)) {
            $objCompraEstado = $this->cargarObjeto($param);
            if ($objCompraEstado != null && $objCompraEstado->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idcompraestado']))
                $where .= " and idcompraestado = " . $param['idcompraestado'];
            if (isset($param['idcompra']))
                $where .= " and idcompra = " . $param['idcompra'];
            if (isset($param['idcompraestadotipo']))
                $where .= " and idcompraestadotipo = " . $param['idcompraestadotipo'];
            if (isset($param['cefechaini']))
                $where .= " and cefechaini = '" . $param['cefechaini'] . "'";
            if (isset($param['cefechafin']))
                $where .= " and cefechafin = '" . $param['cefechafin'] . "'";
        }
        $objCompraEstado = new CompraEstado();
        $arreglo = $objCompraEstado->listar($where);
        return $arreglo;
    }
}