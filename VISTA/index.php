<?php
include_once '../configuracion.php';
$sesion = new Session();
$sesion->validarLogin(true);

include_once './structure/header.php';

$objAbmMenu = new ABMMenu();


$admUsuarios = $objAbmMenu->buscar(['idmenu' => 6]); $listarUsuarios = $objAbmMenu->buscar(['idmenu' => 7]); $agregarUsuario = $objAbmMenu->buscar(['idmenu' => 8]);
$admProductos = $objAbmMenu->buscar(['idmenu' => 9]); $listarProductos = $objAbmMenu->buscar(['idmenu' => 10]); $agregarProductos = $objAbmMenu->buscar(['idmenu' => 11]);// $agregarDescuento = $objAbmMenu->buscar(['idmenu' => 22]);
$admRoles = $objAbmMenu->buscar(['idmenu' => 12]); $listarRoles = $objAbmMenu->buscar(['idmenu' => 13]); $agregarRoles = $objAbmMenu->buscar(['idmenu' => 14]);
$admCompras = $objAbmMenu->buscar(['idmenu' => 19]); $listarCompras = $objAbmMenu->buscar(['idmenu' => 20]);

$admUsuariosEstado = $admUsuarios[0]->getDeshabilitado(); 
$listarUsuariosEstado = $listarUsuarios[0]->getDeshabilitado(); 
$agregarUsuarioEstado = $agregarUsuario[0]->getDeshabilitado(); 
$admProductosEstado = $admProductos[0]->getDeshabilitado(); 
$listarProductosEstado = $listarProductos[0]->getDeshabilitado(); 
$agregarProductosEstado = $agregarProductos[0]->getDeshabilitado(); 
//$agregarDescuentoEstado = $agregarDescuento[0]->getDeshabilitado(); 
$admRolesEstado = $admRoles[0]->getDeshabilitado(); 
$listarRolesEstado = $listarRoles[0]->getDeshabilitado(); 
$agregarRolesEstado = $agregarRoles[0]->getDeshabilitado(); 
$admComprasEstado = $admCompras[0]->getDeshabilitado();
$listarComprasEstado = $listarCompras[0]->getDeshabilitado();
?>

<div class="container mt-4 mb-4 flex-grow-1 d-flex flex-column justify-content-center">

    <div class="row justify-content-center">
        <div class="col-md-8"> 
            <div class="card mb-3 border-0 shadow-sm">
                <?php if ($admUsuariosEstado == 0) { ?>
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false">
                    <span><i class="bi bi-people-fill me-2"></i> Administrar Usuarios</span>
                    <i class="bi bi-chevron-down fs-6"></i> </button>
                      <?php } ?>
                
                <div class="collapse" id="collapseUsuarios">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if ($listarUsuariosEstado == 0) { ?>
                            <a href="administrarUsuarios.php?accion=listar" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-list-ul me-2"></i> Listar Usuarios
                            </a>
                            <?php } ?>
                            <?php if ($agregarUsuarioEstado == 0) { ?>
                            <a href="administrarUsuarios.php?accion=nuevo" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-person-plus-fill me-2"></i> Agregar Usuario
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <?php if ($admProductosEstado == 0) { ?>
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseProductos" aria-expanded="false">
                    <span><i class="bi bi-box-seam-fill me-2"></i> Administrar Productos</span>
                    <i class="bi bi-chevron-down fs-6"></i>
                </button>
                <?php } ?>
                <div class="collapse" id="collapseProductos">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if ($listarProductosEstado == 0) { ?>
                            <a href="prodAdmin.php" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-list-ul me-2"></i> Listar Productos
                            </a>
                            <?php } ?>
                            <?php if ($agregarProductosEstado == 0) { ?>
                            <a href="agregarProd.php" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-plus-circle-fill me-2"></i> Agregar Producto
                            </a>
                            <?php } ?>
                            
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <?php if ($admRolesEstado == 0) { ?>
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseRoles" aria-expanded="false">
                    <span><i class="bi bi-shield-lock-fill me-2"></i> Administrar Roles</span>
                    <i class="bi bi-chevron-down fs-6"></i>
                </button>
                <?php } ?>
                <div class="collapse" id="collapseRoles">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if ($listarRolesEstado == 0) { ?>
                            <a href="listarRoles.php" class="list-group-item list-group-item-action py-3 ps-4">Listar Roles</a>
                            <?php } ?>
                            <?php if ($agregarRolesEstado == 0) { ?>
                            <a href="listarRoles.php" class="list-group-item list-group-item-action py-3 ps-4">Agregar Rol</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseMenu" aria-expanded="false">
                    <span><i class="bi bi-list-task me-2"></i> Administrar Menú</span>
                    <i class="bi bi-chevron-down fs-6"></i>
                </button>

                <div class="collapse" id="collapseMenu">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="./menuAdmin.php" class="list-group-item list-group-item-action py-3 ps-4">Listar Menús</a>
                        </div>
                        <a href="./agregarMenu.php" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-plus-circle-fill me-2"></i> Agregar Menú
                            </a>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <?php if ($admComprasEstado == 0) { ?>
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompras" aria-expanded="false">
                    <span><i class="bi bi-list-task me-2"></i> Administrar Compras</span>
                    <i class="bi bi-chevron-down fs-6"></i>
                </button>
                <?php } ?>

                <div class="collapse" id="collapseCompras">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if ($listarComprasEstado == 0) { ?>
                            <a href="compraAdmin.php" class="list-group-item list-group-item-action py-3 ps-4">Listar Compras</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include_once './structure/footer.php'; ?>