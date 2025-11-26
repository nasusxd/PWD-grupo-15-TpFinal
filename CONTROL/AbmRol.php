<?php

class ABMRol
{

    public function cargarObjeto($param)
    {
        $objRol = null;

        if (array_key_exists('idrol', $param) && array_key_exists('rodescripcion', $param)) {
            $objRol = new Rol();
            $objRol->cargarDatos($param);
        }
        return $objRol;
    }

    public function cargarObjetoConClave($param)
    {
        $objRol = null;
        if (isset($param['idrol'])) {
            $objRol = new Rol();
            $objRol->cargarDatos(['idrol' => $param['idrol']]);
        }
        return $objRol;
    }

    public function alta($param)
    {
        $resp = false;
        $objRol = $this->cargarObjeto($param);
        if ($objRol != null && $objRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if (array_key_exists('idrol', $param)) {
            $objRol = $this->cargarObjetoConClave($param);
            if ($objRol != null && $objRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if (array_key_exists('idrol', $param)) {
            $objRol = $this->cargarObjeto($param);
            if ($objRol != null && $objRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = "true";
        if ($param != null) {
            if (isset($param['idrol']))
                $where .= " and idrol = " . $param['idrol'];
            if (isset($param['rodescripcion']))
                $where .= " and rodescripcion = '" . $param['rodescripcion'] . "'";
        }
        $objRol = new Rol();
        $arreglo = $objRol->listar($where);
        return $arreglo;
    }
    public function crearRol($descripcion)
    {

        // Verificar si ya existe
        $existe = $this->buscar(['rodescripcion' => $descripcion]);
        if (!empty($existe)) {
            return ["success" => false, "mensaje" => "El rol ya existe."];
        }

        // Crear rol
        if ($this->alta(['idrol' => null, 'rodescripcion' => $descripcion])) {
            $nuevo = $this->buscar(['rodescripcion' => $descripcion])[0];

            return [
                "success" => true,
                "mensaje" => "Rol creado correctamente.",
                "id" => $nuevo->getIdRol(),
                "descripcion" => $nuevo->getRoDescripcion()
            ];
        }

        return ["success" => false, "mensaje" => "Error al crear el rol."];
    }
    public function borrarRol($idRol)
    {

        // Roles protegidos (por ej: Admin 1 y Cliente 2)
        if ($idRol == 1 || $idRol == 2) {
            return ["success" => false, "mensaje" => "No se pueden eliminar los roles del sistema."];
        }

        // Verificar si hay usuarios con ese rol
        $objUsuarioRol = new UsuarioRol();
        $asignados = $objUsuarioRol->listar("idrol = " . $idRol);

        if (!empty($asignados)) {
            return [
                "success" => false,
                "mensaje" => "No se puede eliminar el rol porque está asignado a uno o más usuarios."
            ];
        }

        // Borrar permisos del rol
        $abmMenuRol = new ABMMenuRol();
        $permisos = $abmMenuRol->buscar(['idrol' => $idRol]);

        foreach ($permisos as $p) {
            $p->eliminar();
        }

        // Borrar rol
        if ($this->baja(['idrol' => $idRol])) {
            return ["success" => true, "mensaje" => "Rol eliminado correctamente."];
        }

        return ["success" => false, "mensaje" => "No se pudo eliminar el rol."];
    }
}
