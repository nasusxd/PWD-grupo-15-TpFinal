<?php
class Producto
{
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $precio;
    private $procantstock;
    private $proimagen;

    public function __construct($datos = [])
    {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos)
    {
        $this->idproducto = $datos['idproducto'] ?? null;
        $this->pronombre = $datos['pronombre'] ?? null;
        $this->prodetalle = $datos['prodetalle'] ?? null;
        $this->precio = $datos['precio'] ?? null;
        $this->procantstock = $datos['procantstock'] ?? null;
        $this->proimagen = $datos['proimagen'] ?? null;
    }

    public function getIdProducto()
    {
        return $this->idproducto;
    }
    public function getNombre()
    {
        return $this->pronombre;
    }
    public function getDetalle()
    {
        return $this->prodetalle;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getStock()
    {
        return $this->procantstock;
    }

    public function getImagen()
    {
        return $this->proimagen;
    }
    
    public function setIdProducto($id)
    {
        $this->idproducto = $id;
    }

    public function setNombre($nombre)
    {
        $this->pronombre = $nombre;
    }
    public function setDetalle($detalle)
    {
        $this->prodetalle = $detalle;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function setStock($stock)
    {
        $this->procantstock = $stock;
    }

    public function setImagen($img)
    {
        $this->proimagen = $img;
    }


    public function insertar()
    {
        $res = false;
        $baseDatos = new BaseDatos();
        $sql = "INSERT INTO producto (pronombre, prodetalle, precio, procantstock, proimagen) 
                VALUES (:pronombre, :prodetalle, :precio, :procantstock, :proimagen)";

        $stmt = $baseDatos->prepare($sql);
        if ($stmt->execute([
            ':pronombre' => $this->getNombre(),
            ':prodetalle' => $this->getDetalle(),
            ':precio' => $this->getPrecio(),
            ':procantstock' => $this->getStock(),
            ':proimagen' => $this->getImagen()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar()
    {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE producto
                SET pronombre = :pronombre, prodetalle = :prodetalle, precio = :precio, procantstock = :procantstock, proimagen = :proimagen
                WHERE idproducto = :idproducto";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':idproducto' => $this->getIdProducto(),
            ':pronombre' => $this->getNombre(),
            ':prodetalle' => $this->getDetalle(),
            ':precio' => $this->getPrecio(),
            ':procantstock' => $this->getStock(),
            ':proimagen' => $this->getImagen()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE FROM producto WHERE idproducto = :idproducto";
        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':idproducto' => $this->getIdProducto()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function listar($condicion = "")
    {
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();

        $productos = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objProducto = new Producto();
            $objProducto->cargarDatos($fila);
            $productos[] = $objProducto;
        }
        return $productos;
    }
}
