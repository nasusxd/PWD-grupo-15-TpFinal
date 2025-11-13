<?php
class Rol {
    private $rodescripcion;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->rodescripcion = $datos['rodescripcion'] ?? null;
    }

    public function getDescripcion() {
        return $this->rodescripcion;
    }

    public function setDescripcion($descripcion) {
        $this->rodescripcion = $descripcion;
    }

    
    public function insertarRol($rodescripcion) {
        $base = new BaseDatos();
        $sql = "INSERT INTO rol (rodescripcion) VALUES (:desc)";
        $stmt = $base->prepare($sql);
        $stmt->execute([':desc' => $this->rodescripcion]);
    }

    public function modificarRol($rodescripcion) {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE rol 
        SET rodescripcion = :rodescripcion
        WHERE rodescripcion = :rodescripcion";
        $stmt = $baseDatos->prepare($sql);
        $stmt->execute([
            ':nombre' => $rodescripcion
        ]);
    }

    public function listarRol($condicion = "") {
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol";
        if ($condicion != "") $sql .= " WHERE " . $condicion;
        $stmt = $base->prepare($sql);
        $stmt->execute();
        $roles = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario = new Rol();
            $usuario->cargarDatos($fila);
            $roles[] = $rol;
        }
        return $roles;
    }

    public function eliminarRol($rodescripcion) {
        $base = new BaseDatos();
        $sql = "DELETE FROM rol WHERE rodescripcion = :rodescripcion";
        $stmt = $base->prepare($sql);
        $stmt->execute([':rodescripcion' => $rodescripcion]);
    }

}