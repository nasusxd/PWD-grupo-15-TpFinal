<?php
class Compra {
    private $idcompra;
    private $cofecha;
    private $idusuario; // FK a Usuario

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idcompra = $datos['idcompra'] ?? null;
        $this->cofecha = $datos['cofecha'] ?? null;
        $this->idusuario = $datos['idusuario'] ?? null;
    }

    // Getters
    public function getIdCompra() {
        return $this->idcompra;
    }
    public function getFecha() {
        return $this->cofecha;
    }
    public function getIdUsuario() {
        return $this->idusuario;
    }

    // Setters
    public function setFecha($fecha) {
        $this->cofecha = $fecha;
    }
    public function setIdUsuario($idUsuario) {
        $this->idusuario = $idUsuario;
    }
}