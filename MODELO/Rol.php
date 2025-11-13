<?php
class Rol {
    private $idrol;
    private $rodescripcion;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idrol = $datos['idrol'] ?? null;
        $this->rodescripcion = $datos['rodescripcion'] ?? null;
    }

    // Getters
    public function getIdRol() {
        return $this->idrol;
    }
    public function getDescripcion() {
        return $this->rodescripcion;
    }

    // Setters
    public function setDescripcion($descripcion) {
        $this->rodescripcion = $descripcion;
    }
}