<?php
class CompraItem {
    private $idcompraitem;
    private $idproducto; // FK a Producto
    private $idcompra;   // FK a Compra
    private $cicantidad;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idcompraitem = $datos['idcompraitem'] ?? null;
        $this->idproducto = $datos['idproducto'] ?? null;
        $this->idcompra = $datos['idcompra'] ?? null;
        $this->cicantidad = $datos['cicantidad'] ?? null;
    }

    // Getters
    public function getIdCompraItem() {
        return $this->idcompraitem;
    }
    public function getIdProducto() {
        return $this->idproducto;
    }
    public function getIdCompra() {
        return $this->idcompra;
    }
    public function getCantidad() {
        return $this->cicantidad;
    }

    // Setters
    public function setIdProducto($idProducto) {
        $this->idproducto = $idProducto;
    }
    public function setIdCompra($idCompra) {
        $this->idcompra = $idCompra;
    }
    public function setCantidad($cantidad) {
        $this->cicantidad = $cantidad;
    }
}