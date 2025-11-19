<?php

class ABMCompra {
    public function cargarObjeto($param) {
        $objCompra = null;

        if (array_key_exists('idcompra', $param) && array_key_exists('cofecha', $param) && array_key_exists('idusuario', $param)) {
            $objCompra = new Compra();
            $objCompra->cargarDatos($param);
        }
        return $objCompra;
    }

    public function cargarObjetoConClave($param) {
        $objCompra = null;

        if (isset($param['idcompra'])) {
            $objCompra = new Compra();
            $objCompra->cargarDatos(['idcompra' => $param['idcompra']]);
        }
        return $objCompra;
    }

    public function alta($param) {
        $idCompra = -1;
        $objCompra = $this->cargarObjeto($param);

        if ($objCompra != null && $objCompra->insertar()) {
        $idCompra = $objCompra->getIdCompra();
        }
        return $idCompra; // retorna el id
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