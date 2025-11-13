<?php
class Producto {
    private $idproducto;
    private $pronombre; // Nota: en tu BD es INT
    private $prodetalle;
    private $procantstock;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idproducto = $datos['idproducto'] ?? null;
        $this->pronombre = $datos['pronombre'] ?? null;
        $this->prodetalle = $datos['prodetalle'] ?? null;
        $this->procantstock = $datos['procantstock'] ?? null;
    }

    // Getters
    public function getIdProducto() {
        return $this->idproducto;
    }
    public function getNombre() {
        return $this->pronombre;
    }
    public function getDetalle() {
        return $this->prodetalle;
    }
    public function getStock() {
        return $this->procantstock;
    }

    // Setters
    public function setNombre($nombre) {
        $this->pronombre = $nombre;
    }
    public function setDetalle($detalle) {
        $this->prodetalle = $detalle;
    }
    public function setStock($stock) {
        $this->procantstock = $stock;
    }
}