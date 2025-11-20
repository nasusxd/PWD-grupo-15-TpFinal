<?php

class MenuRol
{
    private $idmenu;
    private $idrol;

    public function __construct($datos = [])
    {
        if (!empty($datos)) {
            $this->cargarDatos($datos);
        }
    }

    public function cargarDatos($datos)
    {
        $this->idmenu = $datos['idmenu'] ?? null;
        $this->idrol = $datos['idrol'] ?? null;
    }



    public function getIdMenu()
    {
        return $this->idmenu;
    }

    public function setIdMenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }

    public function getIdRol()
    {
        return $this->idrol;
    }

    public function setidrol($idrol)
    {
        $this->idrol = $idrol;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menurol (idmenu, idrol) VALUES (:idmenu, :idrol)";
        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':idmenu' => $this->getIdMenu(),
            ':idrol' => $this->getIdRol()
        ])) {
            $resp = true;
        }
        return $resp;
    }


    public function modificar()
    {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE menurol 
                SET idmenu = :nombre, idrol = :idrol
                WHERE idmenu = :idmenu AND idrol = :idrol";

        $stmt = $base->prepare($sql);
        if ($stmt->execute([
            ':idmenu' => $this->getIdMenu(),
            ':idrol' => $this->getIdRol()
        ])) {
            $resp = true;
        }
        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE FROM menurol WHERE idmenu = :id AND idrol = :idrol";
        $stmt = $base->prepare($sql);
        if ($stmt->execute([':id' => $this->getIdMenu(), 'idrol' => $this->getIdRol()])) {
            $resp = true;
        }
        return $resp;
    }

    public function listar($condicion = "")
    {
        $base = new BaseDatos();
        $sql = "SELECT * FROM menurol";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }
        $stmt = $base->prepare($sql);
        $stmt->execute();

        $menusRoles = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $objMenuRol = new MenuRol();
            $objMenuRol->cargarDatos($fila);
            $menusRoles[] = $objMenuRol;
        }
        return $menusRoles;
    }
    public function obtenerMenusPorRol($idrol) {
    $base = new BaseDatos();
    $sql = "SELECT menu.* 
            FROM menurol 
            JOIN menu ON menurol.idmenu = menu.idmenu
            WHERE menurol.idrol = :idrol";
            
    $stmt = $base->prepare($sql);
    $stmt->execute([':idrol' => $idrol]);

    $menus = [];
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $menus[] = $fila; 
    }
    return $menus;
}

}
