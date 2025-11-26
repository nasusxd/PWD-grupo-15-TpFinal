<?php
class ABMMenu
{

    public function cargarObjetos($param)
    {
        $objMenu = null;
        if (
            array_key_exists('idmenu', $param) && array_key_exists('menombre', $param) && array_key_exists('medescripcion', $param) &&
            array_key_exists('idpadre', $param) && array_key_exists('medeshabilitado', $param) && array_key_exists('urlMenu', $param)
        ) {
            $objMenu = new Menu();
            $objMenu = $objMenu->cargarDatos($param);
        }
        return $objMenu;
    }

    public function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('idmenu', $param) && array_key_exists('menombre', $param) && array_key_exists('medescripcion', $param)) {
            $obj = new Menu();

            $objMenuPadre = null;
            if (isset($param['idpadre'])) {
                $objMenuPadre = new Menu();
                $objMenuPadre->setIdpadre($param['idpadre']);
                $objMenuPadre->cargar();
            }
            $medireccion = isset($param['medireccion']) ? $param['medireccion'] : null;
            $medeshabilitado = isset($param['medeshabilitado']) ? $param['medeshabilitado'] : null;

            $obj->setear($param['idmenu'], $param['menombre'], $param['medescripcion'], $medireccion, $objMenuPadre, $medeshabilitado);
        }
        return $obj;
    }

    public function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idmenu'])) {
            $obj = new Menu();
            $obj->setIdpadre($param['idmenu']);
        }
        return $obj;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idmenu'] = null;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if ($this->esBajaPermitida($param)) {
            $obj = $this->cargarObjetoConClave($param);
            if ($obj != null && $obj->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function esBajaPermitida($param)
    {

        $resp = false;
        if (isset($param['idmenu'])) {
            $lista = $this->buscar(['idpadre' => $param['idmenu']]);
            if (count($lista) == 0) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->modificar()) {
            $resp = true;
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idmenu']))
                $where .= " and idmenu =" . $param['idmenu'];
            if (isset($param['menombre']))
                $where .= " and menombre ='" . $param['menombre'] . "'";
            if (isset($param['medescripcion']))
                $where .= " and medescripcion ='" . $param['medescripcion'] . "'";
            if (isset($param['idpadre']))
                $where .= " and idpadre =" . $param['idpadre'];
            if (isset($param['medeshabilitado']))
                $where .= " and medeshabilitado ='" . $param['medeshabilitado'] . "'";


            if (isset($param['medireccion']))
                $where .= " and medireccion ='" . $param['medireccion'] . "'";
        }
        $obj = new Menu();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }


    public function cambiarEstado($idMenu, $nuevoEstado)
    {
        $menus = $this->buscar(['idmenu' => $idMenu]);
        if (!empty($menus)) {
            $menu = $menus[0]; 
            $menu->setDeshabilitado($nuevoEstado);
            return $menu->modificar();
        }
        return false;
    }
    public function crearMenu($datos)
    {
        if (
            !isset($datos["pronombre"]) ||
            !isset($datos["prodetalle"]) ||
            !isset($datos["medeshabilitado"])
        ) {
            return ["success" => false, "msg" => "Faltan datos obligatorios."];
        }

        $existe = $this->buscar(['menombre' => $datos['pronombre']]);
        if (count($existe) > 0) {
            return ["success" => false, "msg" => "Ya existe un menú con ese nombre."];
        }
        $paramMenu = [
            "idmenu" => null,
            "menombre" => $datos["pronombre"],
            "medescripcion" => $datos["prodetalle"],
            "idpadre" => $datos["idpadre"] ?? null,
            "medeshabilitado" => $datos["medeshabilitado"],
        ];

        if (!$this->alta($paramMenu)) {
            return ["success" => false, "msg" => "Falló la creación del menú."];
        }
        $nuevoMenuArr = $this->buscar(['menombre' => $datos['pronombre']]);
        if (empty($nuevoMenuArr)) {
            return ["success" => false, "msg" => "Error al recuperar el menú recién creado."];
        }

        $objMenu = $nuevoMenuArr[0];
        $idMenu = $objMenu->getIdMenu();

        if (isset($datos["menuRoles"]) && is_array($datos["menuRoles"])) {

            $abmMenuRol = new ABMMenuRol();

            foreach ($datos["menuRoles"] as $idRol) {
                $paramMenuRol = [
                    "idmenu" => $idMenu,
                    "idrol"  => $idRol
                ];
                $abmMenuRol->alta($paramMenuRol);
            }
        }

        return [
            "success" => true,
            "msg" => "Menú creado correctamente."
        ];
    }

    public function cambiarEstadoMenu($datos)
{
    $idMenu = intval($datos['idMenu']);
    $estado = intval($datos['deshabilitado']);

    $menuEncontrado = $this->buscar(['idmenu' => $idMenu]);

    if (empty($menuEncontrado)) {
        return ["success" => false, "msg" => "Menú no encontrado"];
    }

    $menu = $menuEncontrado[0];

    $param = [
        "idmenu"        => $menu->getIdMenu(),
        "menombre"      => $menu->getNombre(),
        "medescripcion" => $menu->getDescripcion(),
        "idpadre"       => $menu->getIdPadre(),
        "medeshabilitado" => $estado
    ];

    if ($this->modificacion($param)) {
        return ["success" => true];
    }

    return ["success" => false, "msg" => "Error al actualizar el estado"];
}

}
