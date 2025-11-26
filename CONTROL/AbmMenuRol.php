<?php

class ABMMenuRol
{

    public function cargarObjeto($param)
    {
        $obj = null;

        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
            $obj = new MenuRol();
            $obj->cargarDatos($param);
        }
        return $obj;
    }


    public function cargarObjetoConClave($param)
    {
        $objMenuRol = null;

        if (isset($param['idmenu']) && isset($param['idrol'])) {
            $objMenuRol = new MenuRol();
            $objMenuRol->cargarDatos(['idmenu' => $param['idmenu'], 'idrol' => $param['idrol']]);
        }
        return $objMenuRol;
    }

    public function alta($param)
    {
        $resp = false;
        $objMenuRol = $this->cargarObjeto($param);
        if ($objMenuRol != null && $objMenuRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
            $objMenuRol = $this->cargarObjetoConClave($param);
            if ($objMenuRol != null && $objMenuRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
            $objMenuRol = $this->cargarObjeto($param);
            if ($objMenuRol != null && $objMenuRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = "true";
        if ($param != null) {
            if (isset($param['idmenu']))
                $where .= " and idmenu = " . $param['idmenu'];
            if (isset($param['idrol']))
                $where .= " and idrol = " . $param['idrol'];
        }
        $objMenuRol = new MenuRol();
        $arreglo = $objMenuRol->listar($where);
        return $arreglo;
    }
    public function obtenerRolesDeMenu($idMenu)
    {
        $asignaciones = $this->buscar(["idmenu" => $idMenu]);

        $idsRoles = [];
        foreach ($asignaciones as $mr) {
            $idsRoles[] = $mr->getIdRol();
        }

        return $idsRoles;
    }
    public function guardarRolesMenu($idMenu, $rolesSeleccionados)
    {
        $abmMenu = new ABMMenu();

        // =============================
        // 1) BORRAR ROLES DEL MENÚ
        // =============================
        $actuales = $this->buscar(["idmenu" => $idMenu]);

        foreach ($actuales as $mr) {
            $mr->eliminar();
        }

        // =============================
        // 2) INSERTAR ROLES NUEVOS
        // =============================
        foreach ($rolesSeleccionados as $idRol) {
            $this->alta([
                "idmenu" => $idMenu,
                "idrol"  => intval($idRol)
            ]);
        }

        // =============================
        // 3) AGREGAR ROLES AL PADRE
        // =============================
        $menuData = $abmMenu->buscar(['idmenu' => $idMenu]);

        if (!empty($menuData)) {

            $idPadre = $menuData[0]->getIdPadre();

            if ($idPadre != 0) {

                $rolesPadreActuales = $this->buscar(["idmenu" => $idPadre]);
                $rolesPadreIds      = array_map(fn($r) => $r->getIdRol(), $rolesPadreActuales);

                foreach ($rolesSeleccionados as $idRol) {

                    if (!in_array($idRol, $rolesPadreIds)) {

                        $this->alta([
                            "idmenu" => $idPadre,
                            "idrol"  => intval($idRol)
                        ]);
                    }
                }
            }
        }

        return ["success" => true, "msg" => "Roles actualizados correctamente"];
    }
}
