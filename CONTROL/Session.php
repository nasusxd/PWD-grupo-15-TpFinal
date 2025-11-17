<?php

class Session {
    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function iniciar ($usMail, $pass) {
        $resp = false;
        $objABMUsuario = new ABMUsuario();
        $lista = $objABMUsuario->buscar(['usmail' => $usMail]);

        if (count($lista) !== 1) {
            $this->cerrar(); //cierro la sesion si no encuentro el usuario
        } else {
            $usuario = $lista[0];
            
            if ($usuario->getDeshabilitado() !== null) {
                $this->cerrar(); //cierro la sesion si el usuario esta deshabilitado
            }else {
                if (password_verify($pass, $usuario->getPassword())) {
                    $_SESSION['idusuario'] = $usuario->getIdUsuario(); //guardo el idusuario en la session
                    $resp = true;
                } else {
                    $this->cerrar(); //cierro la sesion si la password es incorrecta
                }
            }
        }
        return $resp;
    }

   public function esAdmin() {
    $esAdmin = false;
    if ($this->validar()) {
        $roles = $this->getRol(); 
        
       
        if (in_array(2, $roles)) { 
            $esAdmin = true;
        }
    }
    return $esAdmin;
}

    //valida si la sesion tiene un usuario valido
    public function validar() {
        return isset($_SESSION['idusuario']);
    }

    //devuelve true o false si la sesion esta activa y valida
    public function activa () {
        return session_status() === PHP_SESSION_ACTIVE && $this->validar();
    }

    //devuelve el user logueado o null si no hay user
    public function getUsuario() {
        return $this->activa() ? $_SESSION['idusuario'] : null;
    }

    //devuelve los roles del usuario logueado
    public function getRol() {
        $roles = [];
        $idUsuario = $this->getUsuario();
        if ($idUsuario) {
            $abmUsuarioRol = new ABMUsuarioRol();
            $usuariosRoles = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);
            if (!empty($usuariosRoles)) {
                foreach ($usuariosRoles as $rolObj) {
                    $roles[] = $rolObj->getIdRol();
                }
            }
        }
        return $roles;
    }
    
    public function agregarAlCarrito($idProducto, $cantidad) {
        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$idProducto])) {
            $_SESSION['carrito'][$idProducto] += $cantidad;
        } else {
            $_SESSION['carrito'][$idProducto] = $cantidad;
        }
    }


    public function totalProductosCarrito() {
        $total = 0;
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
                $total += $cantidad;
            }
        }
        return $total;
    }

    public function precioTotalCarrito() {
        $total = 0;
        if (isset($_SESSION['carrito'])) {
            $objProducto = new ABMProducto();
            foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
                $productos = $objProducto->buscar(['idproducto' => $idProducto]);
                if (count($productos) > 0) {
                    $producto = $productos[0];
                    $total += $producto->getPrecio() * $cantidad;
                }
            }
        }
        return $total;
    }



    //cierro la sesion
    public function cerrar() {
        session_unset(); //limpio los datos de la sesion
        session_destroy(); //destruyo la sesion
    } 
}