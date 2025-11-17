<?php 
class ABMProducto  {
  public function cargarObjeto($param) {
    $objProducto = new Producto();

    // Solo seteamos si existe cada campo
    if (isset($param['idproducto']))  $objProducto->setIdProducto($param['idproducto']);
    if (isset($param['pronombre']))   $objProducto->setNombre($param['pronombre']);
    if (isset($param['prodetalle']))  $objProducto->setDetalle($param['prodetalle']);
    if (isset($param['precio']))      $objProducto->setPrecio($param['precio']);
    if (isset($param['procantstock']))$objProducto->setStock($param['procantstock']);
    if (isset($param['proimagen']))   $objProducto->setImagen($param['proimagen']);

    return $objProducto;
}

public function cargarObjetoConClave($param) {
    $obj = null;
    if (isset($param['idproducto'])) {
        $obj = new Producto(); 
        $obj->cargarDatos(['idproducto' => $param['idproducto']]);
    }
    return $obj; 
}
    public function alta($param) {
        $resp = false;
        $nuevoProducto = [
            "idproducto" => null,
            "pronombre" => $param['pronombre'],
            "prodetalle" => $param['prodetalle'],
            "procantstock" => $param['procantstock'],
            "precio" => $param['precio'],
            "proimagen" => $param['proimagen']
        ];

        $objProducto = $this->cargarObjeto($nuevoProducto);
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