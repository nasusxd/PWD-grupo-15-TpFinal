<?php
class ABMProducto
{

    public function cargarObjeto($param)
    {
        $objProducto = null;

        if (
            array_key_exists('idproducto', $param) && array_key_exists('pronombre', $param) && array_key_exists('prodetalle', $param) && array_key_exists('precio', $param)
            && array_key_exists('procantstock', $param) && array_key_exists('proimagen', $param) && array_key_exists('descuento', $param)
        ) {
            $objProducto = new Producto();
            $objProducto->cargarDatos($param);
        }
        return $objProducto;
    }


    public function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idproducto'])) {
            $obj = new Producto();
            $obj->cargarDatos(['idproducto' => $param['idproducto']]);
        }
        return $obj;
    }
    public function alta($param)
    {
        $resp = false;
        $nuevoProducto = [
            "idproducto" => null,
            "pronombre" => $param['pronombre'],
            "prodetalle" => $param['prodetalle'],
            "procantstock" => $param['procantstock'],
            "precio" => $param['precio'],
            "proimagen" => $param['proimagen'],
            "descuento" => $param['descuento']
        ];

        $objProducto = $this->cargarObjeto($nuevoProducto);
        if ($objProducto != null && $objProducto->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if (array_key_exists('idproducto', $param)) {
            $objProducto = $this->cargarObjetoConClave($param);
            if ($objProducto != null && $objProducto->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if (array_key_exists('idproducto', $param)) {
            $objProducto = $this->cargarObjeto($param);
            if ($objProducto != null && $objProducto->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
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

    public function actualizarDescuento($datos)
    {

        if (!isset($datos['idproducto']) || !isset($datos['descuento'])) {
            return [
                "success" => false,
                "mensaje" => "Faltan datos."
            ];
        }

        $idProducto = intval($datos['idproducto']);
        $descuento  = floatval($datos['descuento']);

        // Validaciones
        if ($idProducto <= 0 || $descuento < 0 || $descuento > 100) {
            return [
                "success" => false,
                "mensaje" => "Datos inválidos."
            ];
        }

        // Buscar si existe
        $producto = $this->buscar(["idproducto" => $idProducto]);

        if (empty($producto)) {
            return [
                "success" => false,
                "mensaje" => "El producto no existe."
            ];
        }

        /** @var Producto $prod */
        $prod = $producto[0];

        // Armo parámetros para modificación
        $param = [
            "idproducto"   => $prod->getIdProducto(),
            "pronombre"    => $prod->getNombre(),
            "prodetalle"   => $prod->getDetalle(),
            "precio"       => $prod->getPrecio(),
            "procantstock" => $prod->getStock(),
            "proimagen"    => $prod->getImagen(),
            "descuento"    => $descuento
        ];

        // Modificar
        $resultado = $this->modificacion($param);

        if ($resultado) {
            return [
                "success" => true,
                "mensaje" => "Descuento actualizado con éxito."
            ];
        }

        return [
            "success" => false,
            "mensaje" => "No se pudo guardar el descuento."
        ];
    }

    public function altaConImagen($datos, $files)
    {
        // Validar imagen
        if (!isset($files['proimagen']) || $files['proimagen']['error'] !== 0) {
            return ["success" => false, "message" => "No se seleccionó una imagen."];
        }

        // Preparar archivo
        $nombreArchivo = time() . "_" . $files['proimagen']['name'];
        $tmpArchivo = $files['proimagen']['tmp_name'];
        $carpeta = "../../uploads/";
        $rutaDestino = $carpeta . $nombreArchivo;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        // Mover archivo
        if (!move_uploaded_file($tmpArchivo, $rutaDestino)) {
            return ["success" => false, "message" => "Error al subir la imagen."];
        }

        // Agregar imagen al array
        $datos['proimagen'] = $nombreArchivo;

        // Usar el ALTA tradicional del ABM
        $creado = $this->alta($datos);

        if ($creado) {
            return ["success" => true, "message" => "Producto cargado correctamente."];
        }

        return ["success" => false, "message" => "Error al guardar el producto."];
    }
    public function agregarAlCarrito($datos)
    {
        $sesion = new Session();

        // ------------------------------
        // 1) Validaciones básicas
        // ------------------------------
        if (
            !isset($datos['idproducto']) ||
            !is_numeric($datos['idproducto']) ||
            !isset($datos['cantidad']) ||
            $datos['cantidad'] <= 0
        ) {
            return [
                "success" => false,
                "error" => "Datos inválidos."
            ];
        }

        $idProducto = $datos['idproducto'];
        $cantidadPedida = $datos['cantidad'];

        // ------------------------------
        // 2) Buscar producto
        // ------------------------------
        $productos = $this->buscar(['idproducto' => $idProducto]);

        if (empty($productos)) {
            return [
                "success" => false,
                "mensaje" => "Producto inexistente."
            ];
        }

        $producto = $productos[0];
        $stockDisponible = $producto->getStock();

        // ------------------------------
        // 3) Verificar stock vs carrito actual
        // ------------------------------
        $cantidadEnCarrito = $_SESSION['carrito'][$idProducto] ?? 0;
        $cantidadTotal = $cantidadEnCarrito + $cantidadPedida;

        if ($cantidadTotal > $stockDisponible) {
            return [
                "success" => false,
                "mensaje" => "Stock insuficiente. Solo hay $stockDisponible unidades disponibles."
            ];
        }

        // ------------------------------
        // 4) Agregar al carrito
        // ------------------------------
        $sesion->agregarAlCarrito($idProducto, $cantidadPedida);

        // ------------------------------
        // 5) Recalcular totales
        // ------------------------------
        $totalProductos = $sesion->totalProductosCarrito();
        $totalPrecio = $sesion->precioTotalCarrito();

        // ------------------------------
        // 6) Armar lista de items con precios y descuentos
        // ------------------------------
        $items = [];

        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $idProd => $cant) {

                $prodBuscado = $this->buscar(['idproducto' => $idProd]);

                if (!empty($prodBuscado)) {
                    $p = $prodBuscado[0];

                    $descuento = $p->getDescuento();
                    $precioBase = $p->getPrecio();

                    // aplicar descuento
                    $precioFinal = ($descuento > 0)
                        ? $precioBase * (1 - $descuento / 100)
                        : $precioBase;

                    $items[] = [
                        "id" => $idProd,
                        "nombre" => $p->getNombre(),
                        "cantidad" => $cant,
                        "precioUnitario" => $precioFinal,
                        "subtotal" => $precioFinal * $cant,
                        "descuento" => $descuento
                    ];
                }
            }
        }

        // ------------------------------
        // 7) Retorno final
        // ------------------------------
        return [
            "success" => true,
            "totalProductos" => $totalProductos,
            "totalPrecio" => $totalPrecio,
            "items" => $items
        ];
    }
    public function modificarProducto($datos)
    {
        $respuesta = ["success" => false, "msg" => "Error al modificar el producto."];

        if (!isset($datos['idproducto'])) {
            $respuesta['msg'] = "Falta el ID del producto.";
            return $respuesta;
        }

        // Buscar producto actual
        $productoActual = $this->buscar(['idproducto' => $datos['idproducto']]);

        if (empty($productoActual)) {
            $respuesta['msg'] = "El producto no existe.";
            return $respuesta;
        }

        $productoActual = $productoActual[0];

        // Mantener imagen anterior si no vino una nueva
        if (empty($datos['proimagen'])) {
            $datos['proimagen'] = $productoActual->getImagen();
        }

        // Llamar al método modificacion original
        if ($this->modificacion($datos)) {
            return ["success" => true, "msg" => "Producto modificado."];
        } else {
            return ["success" => false, "msg" => "No se pudo modificar el producto en la BD."];
        }
    }
    public function eliminarProducto($idProducto)
    {
        if (!$idProducto) {
            return false;
        }

        // Llamamos al método baja estándar del ABM
        return $this->baja(['idproducto' => $idProducto]);
    }

    public function obtenerItemsCarrito($carrito)
    {
        $items = [];

        foreach ($carrito as $id => $cant) {
            $prodArr = $this->buscar(['idproducto' => $id]);
            if (!empty($prodArr)) {
                $prod = $prodArr[0];
                $items[] = [
                    "id" => $id,
                    "nombre" => $prod->getNombre(),
                    "cantidad" => $cant
                ];
            }
        }

        return $items;
    }
}
