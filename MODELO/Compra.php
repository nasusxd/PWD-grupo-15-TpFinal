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

    public function getIdCompra() {
        return $this->idcompra;
    }
    public function getFecha() {
        return $this->cofecha;
    }
    public function getIdUsuario() {
        return $this->idusuario;
    }

    public function setFecha($fecha) {
        $this->cofecha = $fecha;
    }
    public function setIdUsuario($idUsuario) {
        $this->idusuario = $idUsuario;
    }

    public function insertar() {
        $res = false;
        $baseDatos = new BaseDatos();
         $sql = "INSERT INTO compra (cofecha, idusuario) 
                VALUES (:cofecha, :idusuario)";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':cofecha' => $this->getFecha(),
            ':idusuario' => $this->getIdUsuario()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE compra 
                SET cofecha = :cofecha, idusuario = :idusuario
                WHERE idcompra = :idcompra";
        
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idcompra' => $this->getIdCompra(),
            ':cofecha' => $this->getFecha(),
            ':idusuario' => $this->getIdUsuario()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE compra  WHERE idcompra = :idcompra";
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idcompra' => $this->getIdCompra()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function listar() {
        $base = new BaseDatos();
        $sql = "SELECT * FROM compra";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();
        
        $compras = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objCompra = new Menu();
            $objCompra->cargarDatos($fila);
            $compras[] = $objCompra;
        }
        return $compras;
    }
}