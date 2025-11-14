<?php

class Session {
    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function iniciar ($nombreUser, $pass) {
        $resp = false;
        $objABMUsuario = new ABMUsuario();
        $param = ['usnombre' => $nombreUser];

        $lista = $objABMUsuario->buscar($param);

        if (count($lista) == 1) {
            $usuario = $lista[0];
            if ($usuario->getDeshabilitado() != '0000-00-00') {
                $this->cerrar(); //cierro la sesion si el usuario esta deshabilitado
            }else {
                if (password_verify($pass, $usuario->getPassword())) {
                    $_SESSION['idusuario'] = $usuario->getIdUsuario(); //guardo el idusuario en la session
                    $resp = true;
                } else {
                    $this->cerrar(); //cierro la sesion si la password es incorrecta
                }
            }
        } else {
            $this->cerrar(); //cierro la sesion si no encuentro el usuario
        }
        return $resp;
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
            $objABMUsuarioRol = new ABMUsuarioRol();
            $param = ['idusuario' => $idUsuario];
            $listaUsuarioRol = $objABMUsuarioRol->buscar($param);
            foreach ($listaUsuarioRol as $usuarioRol) {
                $roles[] = $usuarioRol->getObjRol()->getRodescripcion();
            }
        }
        return $roles;
    }
    //cierro la sesion
    public function cerrar () {
        session_unset();
        session_destroy();
    } 
}