<?php

class Session
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function iniciar($usMail, $pass)
    {
        $resp = false;
        $objABMUsuario = new ABMUsuario();
        $lista = $objABMUsuario->buscar(['usmail' => $usMail]);

        if (count($lista) !== 1) {
            $this->cerrar();
        } else {
            $usuario = $lista[0];

            if ($usuario->getDeshabilitado() !== null) {
                $this->cerrar();
            } else {
                if (password_verify($pass, $usuario->getPassword())) {
                    $_SESSION['idusuario'] = $usuario->getIdUsuario();
                    $resp = true;
                } else {
                    $this->cerrar();
                }
            }
        }
        return $resp;
    }

    public function validar()
    {
        return isset($_SESSION['idusuario']);
    }

    public function esAdmin()
    {
        if (!$this->validar()) {
            return false;
        }

        $idUsuario = $this->getUsuario();
        $roles = $this->getRol();

        if (empty($roles)) {
            return false;
        }

        $abmMenuRol = new ABMMenuRol();
        $abmMenu = new ABMMenu();

        $menusAdmin = $abmMenu->buscar(['idpadre' => 1]);

        if (empty($menusAdmin)) {
            return false;
        }

        foreach ($menusAdmin as $menu) {
            $idMenu = $menu->getIdMenu();
            $permisos = $abmMenuRol->buscar(['idmenu' => $idMenu]);

            foreach ($permisos as $perm) {
                if (in_array($perm->getIdRol(), $roles)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function validarLogin(?bool $requerirAdmin = null, ?int $idMenu = null)
    {

        if (!$this->validar()) {
            header("Location: menu.php");
            exit;
        }

        if ($requerirAdmin === true && !$this->esAdmin()) {
            header("Location: menu.php");
            exit;
        }

        if ($requerirAdmin === false && $this->esAdmin()) {
            header("Location: index.php");
            exit;
        }

        if ($idMenu !== null && !$this->tieneAccesoMenu($idMenu)) {
            header("Location: accesoDenegado.php");
            exit;
        }
    }

    public function tieneAccesoMenu($idMenu)
    {
        if (!$this->validar()) return false;

        $userRoles = $this->getRol();
        $abmMenuRol = new ABMMenuRol();
        $rolesPermitidos = $abmMenuRol->buscar(['idmenu' => $idMenu]);

        if (empty($rolesPermitidos)) {
            return false;
        }

        foreach ($rolesPermitidos as $rel) {
            if (in_array($rel->getIdRol(), $userRoles)) {
                return true;
            }
        }

        return false;
    }

    public function activa()
    {
        return session_status() === PHP_SESSION_ACTIVE && $this->validar();
    }

    public function getUsuario()
    {
        return $this->activa() ? $_SESSION['idusuario'] : null;
    }

    public function getRol()
    {
        $roles = [];
        $idUsuario = $this->getUsuario();

        if ($idUsuario) {
            $abmUsuarioRol = new ABMUsuarioRol();
            $usuariosRoles = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);

            foreach ($usuariosRoles as $rolObj) {
                $roles[] = $rolObj->getIdRol();
            }
        }

        return $roles;
    }

    public function getCarrito()
    {
        return $_SESSION['carrito'] ?? [];
    }

    public function agregarAlCarrito($idProducto, $cantidad)
    {
        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $_SESSION['carrito'][$idProducto] = ($_SESSION['carrito'][$idProducto] ?? 0) + $cantidad;
    }

    public function limpiarCarrito()
    {
        unset($_SESSION['carrito']);
    }

    public function totalProductosCarrito()
    {
        return array_sum($_SESSION['carrito'] ?? []);
    }

    public function precioTotalCarrito()
    {
        $total = 0;
        if (isset($_SESSION['carrito'])) {
            $objProducto = new ABMProducto();
            foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
                $productos = $objProducto->buscar(['idproducto' => $idProducto]);
                if ($productos) {
                    $total += $productos[0]->getPrecio() * $cantidad;
                }
            }
        }
        return $total;
    }

    public function cerrar()
    {
        session_unset();
        session_destroy();
    }
    public function crearSessionConUsuario($usuario)
    {
        $_SESSION['idusuario'] = $usuario->getIdUsuario();
    }
}
