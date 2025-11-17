<?php
class Rol
{
    private $idrol;
    private $rodescripcion;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idrol = "";
        $this->rodescripcion = "";
        $this->mensajeoperacion = "";
    }

    public function cargarDatos($datos)
    {
        $this->setIdRol($datos['idrol']);
        $this->setRoDescripcion($datos['rodescripcion']);
    }

    // Getters
    public function getIdRol()
    {
        return $this->idrol;
    }
    public function getRoDescripcion()
    {
        return $this->rodescripcion;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeoperacion;
    }

    // Setters
    public function setIdRol($valor)
    {
        $this->idrol = $valor;
    }
    public function setRoDescripcion($valor)
    {
        $this->rodescripcion = $valor;
    }
    public function setMensajeOperacion($valor)
    {
        $this->mensajeoperacion = $valor;
    }


    public function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }


        try {
            if ($base->Iniciar()) {
                $res = $base->Ejecutar($sql);
                if ($res > -1) {
                    if ($res > 0) {
                        while ($row = $base->Registro()) {
                            $obj = new Rol();
                            $obj->cargarDatos($row);
                            array_push($arreglo, $obj);
                        }
                    }
                }
            } else {
                $this->setMensajeOperacion("Rol->listar: " . $base->getError());
            }
        } catch (Exception $e) {
            $this->setMensajeOperacion("Rol->listar: " . $e->getMessage());
        }

        return $arreglo;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO rol(rodescripcion) VALUES('" . $this->getRoDescripcion() . "')";

        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($sql)) {
                $this->setIdRol($id);
                $resp = true;
            } else {
                $this->setMensajeOperacion("Rol->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Rol->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE rol SET rodescripcion='" . $this->getRoDescripcion() . "' WHERE idrol=" . $this->getIdRol();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Rol->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Rol->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM rol WHERE idrol=" . $this->getIdRol();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Rol->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Rol->eliminar: " . $base->getError());
        }
        return $resp;
    }
}
