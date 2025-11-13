<?php
class CompraEstadoTipo {
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idcompraestadotipo = $datos['idcompraestadotipo'] ?? null;
        $this->cetdescripcion = $datos['cetdescripcion'] ?? null;
        $this->cetdetalle = $datos['cetdetalle'] ?? null;
    }

    // Getters
    public function getIdCompraEstadoTipo() {
        return $this->idcompraestadotipo;
    }
    public function getDescripcion() {
        return $this->cetdescripcion;
    }
    public function getDetalle() {
        return $this->cetdetalle;
    }

    // Setters
    public function setDescripcion($descripcion) {
        $this->cetdescripcion = $descripcion;
    }
    public function setDetalle($detalle) {
        $this->cetdetalle = $detalle;
    }
}