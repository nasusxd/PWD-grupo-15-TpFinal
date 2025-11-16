<?php
include_once '../configuracion.php';
$sesion = new Session();

if (!$sesion->validar()) {
    header("Location: " . "login.php");
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
    header("Location: " . "menu.php");
    exit;
}
include_once './structure/headerAdmin.php';
?>

<div class="container mt-5">
    <div class="row mt-4">
        <div class="col-md-3">
            <a href="usuarios.php" class="btn btn-primary w-100 mb-3">Administrar Usuarios</a>
        </div>
        <div class="col-md-3">
            <a href="productos.php" class="btn btn-success w-100 mb-3">Administrar Productos</a>
        </div>
        <div class="col-md-3">
            <a href="roles.php" class="btn btn-warning w-100 mb-3">Administrar Roles</a>
        </div>
        <div class="col-md-3">
            <a href="menu.php" class="btn btn-info w-100 mb-3">Administrar Menú</a>
        </div>
    </div>
</div>

<?php
include_once 'structure/footer.php';
?>
