<?php

class ABMCarrito {

    public function getCarrito() {
        return $_SESSION['carrito'] ?? [];
    }

    public function guardarCarrito($carrito) {
        $_SESSION['carrito'] = $carrito;
        return true;
    }

    public function eliminarProducto($idProducto) {
        $carrito = $this->getCarrito();

        if (isset($carrito[$idProducto])) {
            unset($carrito[$idProducto]);
            $this->guardarCarrito($carrito);
            return true;
        }
        return false;
    }

    public function getItemsDetalle() {
        $carrito = $this->getCarrito();
        $items = [];

        $objProducto = new ABMProducto();

        foreach ($carrito as $id => $cantidad) {
            $producto = $objProducto->buscar(['idproducto' => $id])[0] ?? null;

            if ($producto) {
                $items[] = [
                    "id" => $id,
                    "nombre" => $producto->getNombre(),
                    "cantidad" => $cantidad,
                    "precioUnitario" => $producto->getPrecio(),
                    "subtotal" => $producto->getPrecio() * $cantidad
                ];
            }
        }

        return $items;
    }
}
