<?php

class Menu {

    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;
    private $medireccion;

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
        $this->medireccion = $datos['medireccion'] ?? null;
    }

    /* ============================
        GETTERS
    ============================ */
    public function getIdMenu() { return $this->idmenu; }
    public function getNombre() { return $this->menombre; }
    public function getDescripcion() { return $this->medescripcion; }
    public function getIdPadre() { return $this->idpadre; }
    public function getDeshabilitado() { return $this->medeshabilitado; }
    public function getDireccion() { return $this->medireccion; }

    /* ============================
        SETTERS
    ============================ */
    public function setNombre($nombre) { $this->menombre = $nombre; }
    public function setDescripcion($desc) { $this->medescripcion = $desc; }
    public function setIdPadre($idpadre) { $this->idpadre = $idpadre; }
    public function setDeshabilitado($valor) { $this->medeshabilitado = $valor; }
    public function setDireccion($url) { $this->medireccion = $url; }

    /* ============================
        CRUD
    ============================ */

    public function insertar() {
        $base = new BaseDatos();
        $sql = "INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado, medireccion)
                VALUES (:nombre, :desc, :idpadre, :deshabilitado, :dir)";

        $stmt = $base->prepare($sql);
        return $stmt->execute([
            ':nombre' => $this->menombre,
            ':desc' => $this->medescripcion,
            ':idpadre' => $this->idpadre,
            ':deshabilitado' => $this->medeshabilitado,
            ':dir' => $this->medireccion
        ]);
    }

    public function modificar() {
        $base = new BaseDatos();
        $sql = "UPDATE menu 
                SET menombre = :nombre,
                    medescripcion = :desc,
                    idpadre = :idpadre,
                    medeshabilitado = :deshabilitado,
                    medireccion = :dir
                WHERE idmenu = :id";

        $stmt = $base->prepare($sql);

        return $stmt->execute([
            ':nombre' => $this->menombre,
            ':desc' => $this->medescripcion,
            ':idpadre' => $this->idpadre,
            ':deshabilitado' => $this->medeshabilitado,
            ':dir' => $this->medireccion,
            ':id' => $this->idmenu
        ]);
    }

    public function eliminar() {
        $base = new BaseDatos();
        $sql = "DELETE FROM menu WHERE idmenu = :id";

        $stmt = $base->prepare($sql);
        return $stmt->execute([':id' => $this->idmenu]);
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
}
