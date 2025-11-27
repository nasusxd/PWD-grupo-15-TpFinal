<?php

class ABMCompraEstado
{


    public function cargarObjeto($param)
    {
        $objCompraEstado = null;

        if (
            array_key_exists('idcompraestado', $param) && array_key_exists('idcompra', $param) &&
            array_key_exists('idcompraestadotipo', $param) && array_key_exists('cefechaini', $param) &&
            array_key_exists('cefechafin', $param)
        ) {
            $objCompraEstado = new CompraEstado();
            $objCompraEstado->cargarDatos($param);
        }
        return $objCompraEstado;
    }

    public function cargarObjetoConClave($param)
    {
        $objCompraEstado = null;
        if (isset($param['idcompraestado'])) {
            $objCompraEstado = new CompraEstado();
            $objCompraEstado->cargarDatos(['idcompraestado' => $param['idcompraestado']]);
        }
        return $objCompraEstado;
    }

    public function alta($param)
    {
        $resp = false;
        $objCompraEstado = $this->cargarObjeto($param);
        if ($objCompraEstado != null && $objCompraEstado->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if (array_key_exists('idcompraestado', $param)) {
            $objCompraEstado = $this->cargarObjetoConClave($param);
            if ($objCompraEstado != null && $objCompraEstado->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if (array_key_exists('idcompraestado', $param)) {
            $objCompraEstado = $this->cargarObjeto($param);
            if ($objCompraEstado != null && $objCompraEstado->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
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

    public function cambiarEstadoCompra($datos)
    {
        
        if (!isset($datos['idcompra']) || !isset($datos['estado'])) {
            return ["success" => false, "msg" => "Datos incompletos"];
        }

        $idCompra = $datos['idcompra'];
        $nuevoEstado = (int)$datos['estado'];
        $motivo = $datos['motivo'] ?? null;
        $objAbmUsuario = new ABMUsuario();
        $objAbmCompra = new ABMCompra();
        $objAbmProducto = new ABMProducto();
        $objAbmCompraItem = new ABMCompraItem();

        $estados = $this->buscar(['idcompra' => $idCompra]);
        if (empty($estados)) {
            return ["success" => false, "msg" => "No hay estado registrado para esta compra"];
        }

        $ultimoEstado = end($estados);
        $estadoActual = $ultimoEstado->getIdCompraEstadoTipo();


        if ($estadoActual == 3) {
            return ["success" => false, "msg" => "No se puede cambiar una compra ya enviada."];
        }

        if (($estadoActual == 2 && $nuevoEstado == 4) ||
            ($estadoActual == 3 && $nuevoEstado == 4) ||
            $estadoActual == 4
        ) {
            return ["success" => false, "msg" => "No se puede cancelar una compra aceptada."];
        }

        if ($estadoActual == 1 && !in_array($nuevoEstado, [2, 4])) {
            return ["success" => false, "msg" => "Cambio de estado inválido desde iniciada."];
        }

        if ($estadoActual == 2 && $nuevoEstado != 3) {
            return ["success" => false, "msg" => "Cambio de estado inválido desde aceptada."];
        }

        $cerrar = [
            "idcompraestado" => $ultimoEstado->getIdCompraEstado(),
            "idcompra" => $idCompra,
            "idcompraestadotipo" => $estadoActual,
            "cefechaini" => $ultimoEstado->getFechaIni(),
            "cefechafin" => date("Y-m-d H:i:s")
        ];

        if (!$this->modificacion($cerrar)) {
            return ["success" => false, "msg" => "Error al actualizar el estado anterior"];
        }

        $nuevoReg = [
            "idcompraestado" => null,
            "idcompra" => $idCompra,
            "idcompraestadotipo" => $nuevoEstado,
            "cefechaini" => date("Y-m-d H:i:s"),
            "cefechafin" => null,
            "motivoCancelacion" => ($nuevoEstado == 4 ? $motivo : null)
        ];

        if (!$this->alta($nuevoReg)) {
            return ["success" => false, "msg" => "Error al registrar el nuevo estado"];
        }

        if ($nuevoEstado == 4) {

            $items = $objAbmCompraItem->buscar(['idcompra' => $idCompra]);

            foreach ($items as $item) {
                $idProd = $item->getIdProducto();
                $cantidad = $item->getCantidad();

                $producto = $objAbmProducto->buscar(["idproducto" => $idProd])[0];
                $nuevoStock = $producto->getStock() + $cantidad;

                $param = [
                    "idproducto" => $idProd,
                    "pronombre" => $producto->getNombre(),
                    "prodetalle" => $producto->getDetalle(),
                    "precio" => $producto->getPrecio(),
                    "procantstock" => $nuevoStock,
                    "proimagen" => $producto->getImagen(),
                    "descuento" => $producto->getDescuento()
                ];

                $objAbmProducto->modificacion($param);
            }
        }

        $compra = $objAbmCompra->buscar(['idcompra' => $idCompra])[0];
        $usuario = $objAbmUsuario->buscar(["idusuario" => $compra->getIdUsuario()])[0];

        $estadoTexto = match ($nuevoEstado) {
            1 => "Iniciada",
            2 => "Aceptada",
            3 => "Enviada",
            4 => "Cancelada",
            default => "Desconocido",
        };

        enviarCorreoCambioEstado(
            $usuario->getMail(),
            $usuario->getNombre(),
            $idCompra,
            $estadoTexto,
            ($nuevoEstado == 4) ? $motivo : null
        );

        return [
            "success" => true,
            "msg" => "Estado actualizado correctamente",
            "nuevoEstadoTexto" => $estadoTexto
        ];
    }

    public function obtenerHistorialCompra($idCompra)
    {

        $objAbmCompraEstadoTipo = new ABMCompraEstadoTipo();

        $estados = $this->buscar(['idcompra' => $idCompra]);

        if (empty($estados)) {
            return [
                "success" => false,
                "msg" => "No se encontraron estados para esta compra"
            ];
        }

        $data = [];

        foreach ($estados as $estado) {

           
            $tipo = $objAbmCompraEstadoTipo->buscar([
                "idcompraestadotipo" => $estado->getIdCompraEstadoTipo()
            ]);

            $descripcion = !empty($tipo) ? $tipo[0]->getDescripcion() : "Desconocido";

            $data[] = [
                "idcompraestado" => $estado->getIdCompraEstado(),
                "estado" => $estado->getIdCompraEstadoTipo(),
                "descripcion" => $descripcion,
                "inicio" => $estado->getFechaIni(),
                "fin" => $estado->getFechaFin()
            ];
        }

       
        usort($data, function ($a, $b) {
            return strtotime($a["inicio"]) <=> strtotime($b["inicio"]);
        });

        return [
            "success" => true,
            "data" => $data
        ];
    }
}
