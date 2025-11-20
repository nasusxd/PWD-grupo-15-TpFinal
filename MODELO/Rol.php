<?php
class Rol
{
    private $idrol;
    private $rodescripcion;
    private $mensajeoperacion;

    public function __construct($datos = [])
    {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idrol = $datos['idrol'] ?? null;
        $this->rodescripcion = $datos['rodescripcion'] ?? null;
        $this->mensajeoperacion = $datos['mensajeoperacion'] ?? null;
    }

    // Getters
    public function getIdRol()
    {
        return $this->idrol;
    }
    public function getRoDescripcion()
    {
        return $this->rodescripcion;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeoperacion;
    }

    // Setters
    public function setIdRol($valor)
    {
        $this->idrol = $valor;
    }
    public function setRoDescripcion($valor)
    {
        $this->rodescripcion = $valor;
    }
    public function setMensajeOperacion($valor)
    {
        $this->mensajeoperacion = $valor;
    }
    
    public function listar($condicion = "") {
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();

        $roles = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objRol = new Rol();
            $objRol->cargarDatos($fila);
            $roles[] = $objRol;
        }
        return $roles;
    }

    public function insertar() {
        $res = false;
        $baseDatos = new BaseDatos();
        $sql = "INSERT INTO rol (rodescripcion) VALUES (:rodescripcion)";

        $stmt = $baseDatos->prepare($sql);
        if ($stmt->execute([
            ':rodescripcion' => $this->getRoDescripcion()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE rol SET rodescripcion='" . $this->getRoDescripcion() . "' WHERE idrol=" . $this->getIdRol();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Rol->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Rol->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM rol WHERE idrol=" . $this->getIdRol();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Rol->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Rol->eliminar: " . $base->getError());
        }
        return $resp;
    }
}
