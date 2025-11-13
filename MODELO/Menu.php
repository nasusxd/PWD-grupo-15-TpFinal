<?php
class Menu {
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idmenu = $datos['idmenu'] ?? null;
        $this->menombre = $datos['menombre'] ?? null;
        $this->medescripcion = $datos['medescripcion'] ?? null;
        $this->idpadre = $datos['idpadre'] ?? null;
        $this->medeshabilitado = $datos['medeshabilitado'] ?? null;
    }

    // Getters
    public function getIdMenu() {
        return $this->idmenu;
    }
    public function getNombre() {
        return $this->menombre;
    }
    public function getDescripcion() {
        return $this->medescripcion;
    }
    public function getIdPadre() {
        return $this->idpadre;
    }
    public function getDeshabilitado() {
        return $this->medeshabilitado;
    }

    // Setters
    public function setNombre($nombre) {
        $this->menombre = $nombre;
    }
    public function setDescripcion($descripcion) {
        $this->medescripcion = $descripcion;
    }
    public function setIdPadre($idPadre) {
        $this->idpadre = $idPadre;
    }
    public function setDeshabilitado($fecha) {
        $this->medeshabilitado = $fecha;
    }
}