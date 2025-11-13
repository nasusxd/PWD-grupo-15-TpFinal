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

    public function insertar() {
        $res = false;
        $baseDatos = new BaseDatos();
         $sql = "INSERT INTO compraestado (idcompra, idcompraestadotipo, cefechaini, cefechafin) 
                VALUES (:idcompra, :idcompraestadotipo, :getFechaIni, :cefechafin)";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':idcompra' => $this->getIdCompra(),
            ':idcompraestadotipo' => $this->getIdCompraEstadoTipo(),
            ':getFechaIni' => $this->getFechaIni(),
            ':cefechafin' => $this->getFechaFin()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE compraestado 
                SET idcompra = :idcompra, idcompraestadotipo = :idcompraestadotipo, cefechaini = :cefechaini, cefechafin = :cefechafin
                WHERE idcompraestado = :idcompraestado";
        
            $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idcompra' => $this->getIdCompra(),
            ':idcompraestadotipo' => $this->getIdCompraEstadoTipo(),
            ':getFechaIni' => $this->getFechaIni(),
            ':cefechafin' => $this->getFechaFin(),
            ':idcompraestado' => $this->getIdCompraEstado()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE compraestado  WHERE idcompraestado = :idcompraestado";
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idcompraestado' => $this->getIdCompraEstado(),
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function listar($condicion = "") {
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestado";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();
        
        $comprasEstados = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objCompraEstado = new Menu();
            $objCompraEstado->cargarDatos($fila);
            $comprasEstados[] = $objCompraEstado;
        }
        return $comprasEstados;
    }


}