<?php
include_once '../configuracion.php';
$sesion = new Session();

if (!$sesion->validar()) {
    header("Location: login.php");
    exit;
}

$idUsuario = $sesion->getUsuario();
$objUsuarioRol = new ABMUsuarioRol();
$roles = $objUsuarioRol->buscar(['idusuario' => $idUsuario]);

$esAdmin = false;
foreach ($roles as $rol) {
    if ($rol->getIdRol() == 2) {
        $esAdmin = true;
        break;
    }
}

if (!$esAdmin) {
    header("Location: menu.php");
    exit;
}

include_once './structure/headerAdmin.php';
?>
<style>
    .admin-card {
        border: none;
        margin-bottom: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .admin-header {
        background: #0d6efd;
        color: white;
        padding: 12px 20px;
        font-size: 1.1rem;
        font-weight: 500;
        cursor: pointer;
    }

    .admin-header:hover {
        background: #0b5ed7;
    }

    .admin-list .list-group-item {
        border: none;
        padding: 12px 20px;
        font-size: 0.95rem;
    }

    .admin-list .list-group-item a {
        text-decoration: none;
        color: #0d6efd;
    }

    .admin-list .list-group-item:hover {
        background: #f5f7ff;
    }
    
</style>
<div class="container mt-5">

    <div class="admin-card card">
        <div class="admin-header" data-bs-toggle="collapse" data-bs-target="#usuariosCollapse">
            Administrar Usuarios
        </div>
        <div id="usuariosCollapse" class="collapse admin-list">
    <ul class="list-group">
        <li class="list-group-item">
            <a href="administrarUsuarios.php?accion=listar">admiistrar usuarios</a>
        </li>
       
    </ul>
</div>
    </div>

    <div class="admin-card card">
        <div class="admin-header" data-bs-toggle="collapse" data-bs-target="#productosCollapse">
            Administrar Productos
        </div>
        <div id="productosCollapse" class="collapse admin-list">
            <li class="list-group-item"><a href="prodAdmin.php">Administrar Productos</a></li>
        </div>
    </div>

    <div class="admin-card card">
        <div class="admin-header" data-bs-toggle="collapse" data-bs-target="#rolesCollapse">
            Administrar Roles
        </div>
        <div id="rolesCollapse" class="collapse admin-list">
            <ul class="list-group">
                <li class="list-group-item"><a href="roles_listar.php">Listar Roles</a></li>
                <li class="list-group-item"><a href="roles_alta.php">Agregar Rol</a></li>
                <li class="list-group-item"><a href="roles_modificar.php">Modificar Rol</a></li>
                <li class="list-group-item"><a href="roles_eliminar.php">Eliminar Rol</a></li>
            </ul>
        </div>
    </div>

    <div class="admin-card card">
        <div class="admin-header" data-bs-toggle="collapse" data-bs-target="#menuCollapse">
            Administrar Menu
        </div>
        <div id="menuCollapse" class="collapse admin-list">
            <ul class="list-group">
                <li class="list-group-item"><a href="menu_listar.php">Listar Menu</a></li>
                <li class="list-group-item"><a href="menu_alta.php">Agregar Opcion</a></li>
                <li class="list-group-item"><a href="menu_modificar.php">Modificar Menu</a></li>
                <li class="list-group-item"><a href="menu_eliminar.php">Eliminar Opcion</a></li>
            </ul>
        </div>
    </div>

</div>


<?php include_once './structure/footer.php'; ?>
