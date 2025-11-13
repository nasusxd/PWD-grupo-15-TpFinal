<?php
class CompraItem {
    private $idcompraitem;
    private $idproducto; // FK a Producto
    private $idcompra;   // FK a Compra
    private $cicantidad;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idcompraitem = $datos['idcompraitem'] ?? null;
        $this->idproducto = $datos['idproducto'] ?? null;
        $this->idcompra = $datos['idcompra'] ?? null;
        $this->cicantidad = $datos['cicantidad'] ?? null;
    }

    public function getIdCompraItem() {
        return $this->idcompraitem;
    }
    public function getIdProducto() {
        return $this->idproducto;
    }
    public function getIdCompra() {
        return $this->idcompra;
    }
    public function getCantidad() {
        return $this->cicantidad;
    }

    public function setIdProducto($idProducto) {
        $this->idproducto = $idProducto;
    }
    public function setIdCompra($idCompra) {
        $this->idcompra = $idCompra;
    }
    public function setCantidad($cantidad) {
        $this->cicantidad = $cantidad;
    }

    public function insertar() {
        $res = false;
        $baseDatos = new BaseDatos();
         $sql = "INSERT INTO compraitem  (idproducto, idcompra, cicantidad) 
                VALUES (:idproducto, :idcompra, :cicantidad)";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':idproducto' => $this->getIdProducto(),
            ':idcompra' => $this->getIdCompra(),
            ':cicantidad' => $this->getCantidad(),

        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE compraitem 
                SET idproducto = :idproducto, idcompra = :idcompra, cicantidad = :cicantidad
                WHERE idcompraitem = :idcompraitem";
        
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idproducto' => $this->getIdProducto(),
            ':idcompra' => $this->getIdCompra(),
            ':cicantidad' => $this->getCantidad(),
            ':idcompraitem' => $this->getIdCompraItem(),

            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE compraitem WHERE idcompraitem = :idcompraitem";
        $stmt = $base->prepare($sql);
            if ($stmt->execute([
            ':idcompraitem' => $this->getIdCompraItem()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function listar() {
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraitem";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();
        
        $comprasItems = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objCompraItem = new Menu();
            $objCompraItem->cargarDatos($fila);
            $comprasItems[] = $objCompraItem;
        }
        return $comprasItems;
    }
}