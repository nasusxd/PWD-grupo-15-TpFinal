<?php

class ABMCompra {
    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idcompra', $param) && array_key_exists('cofecha', $param) && array_key_exists('idusuario', $param)) {
            $objCompra = new Compra();
            $objCompra->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;

        if (isset($param['idcompra'])) {
            $objCompra = new Compra();
            $objCompra->cargarDatos(['idcompra' => $param['idcompra']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objCompra = $this->cargarObjeto($param);
        if ($objCompra != null && $objCompra->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idcompra', $param)) {
            $objCompra = $this->cargarObjetoConClave($param);
            if ($objCompra != null && $objCompra->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idcompra', $param)) {
            $objCompra = $this->cargarObjeto($param);
            if ($objCompra != null && $objCompra->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idcompra']))
                $where .= " and idcompra = " . $param['idcompra'];
            if (isset($param['cofecha']))
                $where .= " and cofecha = '" . $param['cofecha'] . "'";
            if (isset($param['idusuario']))
                $where .= " and idusuario = " . $param['idusuario'];
        }
        $objCompra = new Compra();
        $arreglo = $objCompra->listar($where);
        return $arreglo;
    }
}