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

    public function getIdCompraEstadoTipo() {
        return $this->idcompraestadotipo;
    }
    public function getDescripcion() {
        return $this->cetdescripcion;
    }
    public function getDetalle() {
        return $this->cetdetalle;
    }

    public function setDescripcion($descripcion) {
        $this->cetdescripcion = $descripcion;
    }
    public function setDetalle($detalle) {
        $this->cetdetalle = $detalle;
    }

    public function insertar() {
        $res = false;
        $baseDatos = new BaseDatos();
         $sql = "INSERT INTO compraestadotipo (cetdescripcion, cetdetalle) 
                VALUES (:cetdescripcion, :cetdetalle)";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':cetdescripcion' => $this->getDescripcion(),
            ':cetdetalle' => $this->getDetalle()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE compraestadotipo 
                SET cetdescripcion = :cetdescripcion, cetdetalle = :cetdetalle
                WHERE idcompraestadotipo = :idcompraestadotipo";

        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':cetdescripcion' => $this->getDescripcion(),
            ':cetdetalle' => $this->getDetalle(),
            ':idcompraestadotipo' => $this->getIdCompraEstadoTipo()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE compraestadotipo  WHERE idcompraestadotipo = :idcompraestadotipo";
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idcompraestadotipo' => $this->getIdCompraEstadoTipo(),
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function listar() {
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();
        
        $comprasEstadoTipo = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objCompraEstadoTipo = new Menu();
            $objCompraEstadoTipo->cargarDatos($fila);
            $comprasEstadoTipo[] = $objCompraEstadoTipo;
        }
        return $comprasEstadoTipo;
    }
}