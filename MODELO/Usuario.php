<?php
class Usuario {
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;

    public function __construct($datos = []) {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos) {
        $this->idusuario = $datos['idusuario'] ?? null;
        $this->usnombre = $datos['usnombre'] ?? null;
        $this->uspass = $datos['uspass'] ?? null;
        $this->usmail = $datos['usmail'] ?? null;
        $this->usdeshabilitado = $datos['usdeshabilitado'] ?? null;
    }
    // Getters
    public function getIdUsuario() {
        return $this->idusuario;
    }
    public function getNombre() {
        return $this->usnombre;
    }
    public function getPassword() {
        return $this->uspass;
    }
    public function getMail() {
        return $this->usmail;
    }
    public function getDeshabilitado() {
        return $this->usdeshabilitado;
    }

    // Setters
    public function setNombre($nombre) {
        $this->usnombre = $nombre;
    }
    public function setPassword($pass) {
        $this->uspass = $pass;
    }
    public function setMail($mail) {
        $this->usmail = $mail;
    }
    public function setDeshabilitado($fecha) {
        $this->usdeshabilitado = $fecha;
    }

    public function insertarUsuario() {
        $hashContrasenia = password_hash($this->getPassword(), PASSWORD_BCRYPT);
        $baseDatos = new BaseDatos();
        $sql = "INSERT INTO usuario (usnombre, uspass, usmail, usdeshabilitado) 
        VALUES (:nombre, :contrasenia, :mail, :deshabilitado)";
        $stmt = $baseDatos->prepare($sql);
        $res = $stmt->execute([
            ':nombre' => $this->getNombre(),
            ':contrasenia' => $hashContrasenia,
            ':mail' => $this->getMail(),
            ':deshabilitado' => $this->getDeshabilitado()
        ]);
        if ($res) {
            $this->idusuario = $baseDatos->lastInsertId();
        }
        return $res;
    }

    public function buscarUsuario($nombreUsuario) {
        $baseDatos = new BaseDatos();
        $sql = "SELECT * FROM usuario WHERE usnombre = :nombre";
        $stmt = $baseDatos->prepare($sql);
        $stmt->execute([':nombre' => $nombreUsuario]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //se modifica por la cookie
    public function modificarUsuario($datos) {
        $baseDatos = new BaseDatos();
        $sql = "UPDATE usuario 
        SET usnombre = :nombre, usmail = :mail, uspass = :pass 
        WHERE idusuario = :id";

        $stmt = $baseDatos->prepare($sql);
        $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':mail' => $datos['mail'],
            ':pass' => password_hash($datos['pass'], PASSWORD_BCRYPT),
            ':id' => $datos['idusuario']
        ]);
    }

    //se elimina por la cookie
    public function eliminarUsuario($idUsuario) {
        $res = false;
        $baseDatos = new BaseDatos();
        $sql = "DELETE FROM usuario WHERE idusuario = :id";
        $stmt = $baseDatos->prepare($sql);
        if($stmt->execute([':id' => $idUsuario])) $res = true;
        return $res;
    }

    public function listarUsuarios($condicion = "") {
        $baseDatos = new BaseDatos();
        $sql = "SELECT * FROM usuario";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $baseDatos->prepare($sql);
        $stmt->execute();
        $usuarios = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario = new Usuario();
            $usuario->cargarDatos($fila);
            $usuarios[] = $usuario;
        }
        return $usuarios;
    }
}