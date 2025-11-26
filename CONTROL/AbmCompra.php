<?php

class ABMCompra
{
    public function cargarObjeto($param)
    {
        $objCompra = null;

        if (array_key_exists('idcompra', $param) && array_key_exists('cofecha', $param) && array_key_exists('idusuario', $param)) {
            $objCompra = new Compra();
            $objCompra->cargarDatos($param);
        }
        return $objCompra;
    }

    public function cargarObjetoConClave($param)
    {
        $objCompra = null;

        if (isset($param['idcompra'])) {
            $objCompra = new Compra();
            $objCompra->cargarDatos(['idcompra' => $param['idcompra']]);
        }
        return $objCompra;
    }

    public function alta($param)
    {
        $idCompra = -1;
        $objCompra = $this->cargarObjeto($param);

        if ($objCompra != null && $objCompra->insertar()) {
            $idCompra = $objCompra->getIdCompra();
        }
        return $idCompra;
    }

    public function baja($param)
    {
        $resp = false;
        if (array_key_exists('idcompra', $param)) {
            $objCompra = $this->cargarObjetoConClave($param);
            if ($objCompra != null && $objCompra->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if (array_key_exists('idcompra', $param)) {
            $objCompra = $this->cargarObjeto($param);
            if ($objCompra != null && $objCompra->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
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

    public function procesarCompra($mail, $idUsuario, $carrito)
    {

        // Validar email
        $abmUsuario = new ABMUsuario();
        $usuario = $abmUsuario->buscar(['usmail' => $mail]);

        if (empty($usuario)) {
            return ['success' => false, 'msg' => 'No existe un usuario con ese email'];
        }

        // Enviar correo
        $enviado = enviarCorreoResumen($mail, $carrito);
        if ($enviado !== true) {
            return ['success' => false, 'msg' => $enviado];
        }

        // Crear compra
        $fechaActual = date("Y-m-d H:i:s");
        $paramCompra = [
            "idcompra" => null,
            "cofecha" => $fechaActual,
            "idusuario" => $idUsuario
        ];

        $idCompra = $this->alta($paramCompra);
        if (!$idCompra) {
            return ['success' => false, 'msg' => 'Error al crear la compra'];
        }

        // ABMs auxiliares
        $abmItem = new ABMCompraItem();
        $abmProducto = new ABMProducto();

        // Crear items + actualizar stock
        foreach ($carrito as $idProducto => $cantidad) {

            $paramItem = [
                "idcompraitem" => null,
                "idproducto" => $idProducto,
                "idcompra" => $idCompra,
                "cicantidad" => $cantidad
            ];
            $abmItem->alta($paramItem);

            $producto = $abmProducto->buscar(['idproducto' => $idProducto])[0];
            $nuevoStock = $producto->getStock() - $cantidad;

            $paramMod = [
                'idproducto' => $idProducto,
                'pronombre' => $producto->getNombre(),
                'prodetalle' => $producto->getDetalle(),
                'precio' => $producto->getPrecio(),
                'procantstock' => $nuevoStock,
                'descuento' => $producto->getDescuento(),
                'proimagen' => $producto->getImagen()
            ];

            if (!$abmProducto->modificacion($paramMod)) {
                return ['success' => false, 'msg' => 'Error al actualizar el stock de un producto'];
            }
        }

       
        $abmEstado = new ABMCompraEstado();
        $paramEstado = [
            "idcompraestado" => null,
            "idcompra" => $idCompra,
            "idcompraestadotipo" => 1, 
            "cefechaini" => $fechaActual,
            "cefechafin" => null
        ];
        $abmEstado->alta($paramEstado);

        return [
            "success" => true,
            "msg" => "Compra procesada correctamente",
            "idcompra" => $idCompra
        ];
    }
}
