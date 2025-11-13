<?php
class UsuarioRol {
    private $idusuario;
    private $idrol;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idusuario = $datos['idusuario'] ?? null; 
        $this->idrol = $datos['idrol'] ?? null;
    }

    public function getIdUsuario() {
        return $this->idusuario;
    }

    public function getIdRol() {
        return $this->idrol;
    }
    
    public function setIdUsuario($idusuario) {
        $this->idusuario = $idusuario;
    }

    public function setIdRol($idrol) {
        $this->idrol = $idrol;
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "INSERT INTO usuariorol (idusuario, idrol) 
                VALUES (:idusuario, :idrol)";
        
        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':idusuario' => $this->getIdUsuario(),
            ':idrol' => $this->getIdRol()
        ])) $resp = true;
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE FROM usuariorol 
                WHERE idusuario = :idusuario AND idrol = :idrol";
        
        $stmt = $base->prepare($sql);
        if($stmt->execute([
            ':idusuario' => $this->getIdUsuario(),
            ':idrol' => $this->getIdRol()
        ])) $resp = true;
        return $resp;
    }

    public static function listar($condicion = "") {
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuariorol";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        
        $stmt = $base->prepare($sql);
        $stmt->execute();
        
        $rolesUsuario = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->cargarDatos($fila);
            $rolesUsuario[] = $objUsuarioRol;
        }
        return $rolesUsuario;
    }
}