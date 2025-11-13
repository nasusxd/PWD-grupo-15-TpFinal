<?php
class Producto {
    private $idproducto;
    private $pronombre; // Nota: en tu BD es INT
    private $prodetalle;
    private $procantstock;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idproducto = $datos['idproducto'] ?? null;
        $this->pronombre = $datos['pronombre'] ?? null;
        $this->prodetalle = $datos['prodetalle'] ?? null;
        $this->procantstock = $datos['procantstock'] ?? null;
    }

    public function getIdProducto() {
        return $this->idproducto;
    }
    public function getNombre() {
        return $this->pronombre;
    }
    public function getDetalle() {
        return $this->prodetalle;
    }
    public function getStock() {
        return $this->procantstock;
    }

    public function setNombre($nombre) {
        $this->pronombre = $nombre;
    }
    public function setDetalle($detalle) {
        $this->prodetalle = $detalle;
    }
    public function setStock($stock) {
        $this->procantstock = $stock;
    }

    
    public function insertar() {
        $res = false;
        $baseDatos = new BaseDatos();
         $sql = "INSERT INTO producto (pronombre, prodetalle, procantstock) 
                VALUES (:pronombre, :prodetalle, :procantstock)";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':pronombre' => $this->getNombre(),
            ':prodetalle' => $this->getDetalle(),
            ':procantstock' => $this->getStock(),
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE producto
                SET pronombre = :pronombre, prodetalle = :prodetalle, procantstock = :procantstock
                WHERE idproducto = :idproducto";
        
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idproducto' => $this->getIdProducto(),
            ':pronombre' => $this->getNombre(),
            ':prodetalle' => $this->getDetalle(),
            ':procantstock' => $this->getStock()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE producto WHERE idproducto = :idproducto";
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idproducto' => $this->getIdProducto()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function listar() {
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();
        
        $productos = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objProducto = new Menu();
            $objProducto->cargarDatos($fila);
            $productos[] = $objProducto;
        }
        return $productos;
    }
}