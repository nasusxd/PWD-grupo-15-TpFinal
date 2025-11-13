<?php 
class ABMProducto  {
    public function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idproducto', $param) && array_key_exists('pronombre', $param)) {
            $objProducto = new Producto();
            $objProducto->cargarDatos($param);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idproducto'])) {
            $objProducto = new Producto();
            $objProducto->cargarDatos(['idproducto' => $param['idproducto']]);
        }
        return $obj;
    }

    public function alta($param) {
        $resp = false;
        $objProducto = $this->cargarObjeto($param);
        if ($objProducto != null && $objProducto->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;
        if (array_key_exists('idproducto', $param)) {
            $objProducto = $this->cargarObjetoConClave($param);
            if ($objProducto != null && $objProducto->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;
        if (array_key_exists('idproducto', $param)) {
            $objProducto = $this->cargarObjeto($param);
            if ($objProducto != null && $objProducto->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idproducto']))
                $where .= " and idproducto = " . $param['idproducto'];
            if (isset($param['pronombre']))
                $where .= " and pronombre = '" . $param['pronombre'];
            if (isset($param['prodetalle']))
                $where .= " and prodetalle = '" . $param['prodetalle'];
            if (isset($param['procantstock'])) {
               $where .= " and procantstock = '" . $param['procantstock'];
            }
        }
        $objProducto = new Producto();
        $arreglo = $objProducto->listar($where);
        return $arreglo;
    }
}