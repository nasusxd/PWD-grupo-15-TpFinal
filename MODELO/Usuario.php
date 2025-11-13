<?php
class Usuario {
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idusuario = $datos['idusuario'] ?? null;
        $this->usnombre = $datos['usnombre'] ?? null;
        $this->uspass = $datos['uspass'] ?? null;
        $this->usmail = $datos['usmail'] ?? null;
        $this->usdeshabilitado = $datos['usdeshabilitado'] ?? null;
    }

    // Getters
    public function getIdUsuario() {
        return $this->idusuario;
    }
    public function getNombre() {
        return $this->usnombre;
    }
    public function getPassword() {
        return $this->uspass;
    }
    public function getMail() {
        return $this->usmail;
    }
    public function getDeshabilitado() {
        return $this->usdeshabilitado;
    }

    // Setters
    public function setNombre($nombre) {
        $this->usnombre = $nombre;
    }
    public function setPassword($pass) {
        $this->uspass = $pass;
    }
    public function setMail($mail) {
        $this->usmail = $mail;
    }
    public function setDeshabilitado($fecha) {
        $this->usdeshabilitado = $fecha;
    }
}