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

    public function setNombre($nombre) { 
        $this->menombre = $nombre; 
    }
    public function setDescripcion($desc) {
         $this->medescripcion = $desc; 
    }
    public function setIdPadre($idpadre) {
    $this->idpadre = $idpadre; 
    }
    public function setDeshabilitado($fecha) {
        $this->medeshabilitado = $fecha; 
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado) 
                VALUES (:nombre, :desc, :idpadre, :deshabilitado)";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':nombre' => $this->getNombre(),
            ':desc' => $this->getDescripcion(),
            ':idpadre' => $this->getIdPadre() ?? null, 
            ':deshabilitado' => $this->getDeshabilitado() ?? null 
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE menu 
                SET menombre = :nombre, medescripcion = :desc, idpadre = :idpadre, medeshabilitado = :deshabilitado
                WHERE idmenu = :id";
        
            $stmt = $base->prepare($sql);
            if ($stmt->execute([
                ':nombre' => $this->getNombre(),
                ':desc' => $this->getDescripcion(),
                ':idpadre' => $this->getIdPadre() ?? null,
                ':deshabilitado' => $this->getDeshabilitado() ?? null,
                ':id' => $this->getIdMenu()
            ])) {
                $resp = true;
            }
        return $resp;
    }

    public function listar($condicion = "") {
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();
        
        $menus = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objMenu = new Menu();
            $objMenu->cargarDatos($fila);
            $menus[] = $objMenu;
        }
        return $menus;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE FROM menu WHERE idmenu = :id";
        $stmt = $base->prepare($sql);
        if ($stmt->execute([':id' => $this->getIdMenu()])) {
            $resp = true;
        }
        return $resp;
    }
}