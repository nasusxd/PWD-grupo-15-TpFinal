<?php

class ABMCompraItem {

    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idcompraitem', $param) && array_key_exists('idproducto', $param) && array_key_exists('idcompra', $param) && array_key_exists('cicantidad', $param)) {
            $objCompraItem = new CompraItem();
            $objCompraItem->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idcompraitem'])) {
            $objCompraItem = new CompraItem();
            $objCompraItem->cargarDatos(['idcompraitem' => $param['idcompraitem']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objCompraItem = $this->cargarObjeto($param);
        if ($objCompraItem != null && $objCompraItem->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idcompraitem', $param)) {
            $objCompraItem = $this->cargarObjetoConClave($param);
            if ($objCompraItem != null && $objCompraItem->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idcompraitem', $param)) {
            $objCompraItem = $this->cargarObjeto($param);
            if ($objCompraItem != null && $objCompraItem->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idcompraitem']))
                $where .= " and idcompraitem = " . $param['idcompraitem'];
            if (isset($param['idproducto']))
                $where .= " and idproducto = " . $param['idproducto'];
            if (isset($param['idcompra']))
                $where .= " and idcompra = " . $param['idcompra'];
            if (isset($param['cicantidad']))
                $where .= " and cicantidad = " . $param['cicantidad'];
        }
        $objCompraItem = new CompraItem();
        $arreglo = $objCompraItem->listar($where);
        return $arreglo;
    }
}