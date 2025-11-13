<?php
class CompraEstado {
    private $idcompraestado;
    private $idcompra;
    private $idcompraestadotipo;
    private $cefechaini;
    private $cefechafin;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idcompraestado = $datos['idcompraestado'] ?? null;
        $this->idcompra = $datos['idcompra'] ?? null;
        $this->idcompraestadotipo = $datos['idcompraestadotipo'] ?? null;
        $this->cefechaini = $datos['cefechaini'] ?? null;
        $this->cefechafin = $datos['cefechafin'] ?? null;
    }

    // Getters
    public function getIdCompraEstado() {
        return $this->idcompraestado;
    }
    public function getIdCompra() {
        return $this->idcompra;
    }
    public function getIdCompraEstadoTipo() {
        return $this->idcompraestadotipo;
    }
    public function getFechaIni() {
        return $this->cefechaini;
    }
    public function getFechaFin() {
        return $this->cefechafin;
    }

    // Setters
    public function setIdCompra($idCompra) {
        $this->idcompra = $idCompra;
    }
    public function setIdCompraEstadoTipo($idTipo) {
        $this->idcompraestadotipo = $idTipo;
    }
    public function setFechaIni($fecha) {
        $this->cefechaini = $fecha;
    }
    public function setFechaFin($fecha) {
        $this->cefechafin = $fecha;
    }
}