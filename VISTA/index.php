<?php
include_once '../configuracion.php';
$sesion = new Session();

// VALIDACIONES DE SEGURIDAD
if (!$sesion->validar()) {
    header("Location: login.php");
    exit;
}
if (!$sesion->esAdmin()) {
    header("Location: menu.php");
    exit;
}

include_once './structure/headerAdmin.php';
?>

<div class="container mt-4 mb-4 flex-grow-1 d-flex flex-column justify-content-center">

    <div class="row justify-content-center">
        <div class="col-md-8"> <div class="card mb-3 border-0 shadow-sm">
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false">
                    <span><i class="bi bi-people-fill me-2"></i> Administrar Usuarios</span>
                    <i class="bi bi-chevron-down fs-6"></i> </button>
                
                <div class="collapse" id="collapseUsuarios">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="administrarUsuarios.php?accion=listar" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-list-ul me-2"></i> Listar Usuarios
                            </a>
                            <a href="administrarUsuarios.php?accion=nuevo" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-person-plus-fill me-2"></i> Agregar Usuario
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseProductos" aria-expanded="false">
                    <span><i class="bi bi-box-seam-fill me-2"></i> Administrar Productos</span>
                    <i class="bi bi-chevron-down fs-6"></i>
                </button>

                <div class="collapse" id="collapseProductos">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="prodAdmin.php" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-list-ul me-2"></i> Listar Productos
                            </a>
                            <a href="agregarProd.php" class="list-group-item list-group-item-action py-3 ps-4">
                                <i class="bi bi-plus-circle-fill me-2"></i> Agregar Producto
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseRoles" aria-expanded="false">
                    <span><i class="bi bi-shield-lock-fill me-2"></i> Administrar Roles</span>
                    <i class="bi bi-chevron-down fs-6"></i>
                </button>

                <div class="collapse" id="collapseRoles">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="roles_listar.php" class="list-group-item list-group-item-action py-3 ps-4">Listar Roles</a>
                            <a href="roles_alta.php" class="list-group-item list-group-item-action py-3 ps-4">Agregar Rol</a>
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
                            <a href="menu_listar.php" class="list-group-item list-group-item-action py-3 ps-4">Listar Opciones</a>
                            <a href="menu_alta.php" class="list-group-item list-group-item-action py-3 ps-4">Agregar Opción</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex justify-content-between align-items-center" 
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompras" aria-expanded="false">
                    <span><i class="bi bi-list-task me-2"></i> Administrar Compras</span>
                    <i class="bi bi-chevron-down fs-6"></i>
                </button>

                <div class="collapse" id="collapseCompras">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="compraAdmin.php" class="list-group-item list-group-item-action py-3 ps-4">Listar Compras</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include_once './structure/footer.php'; ?>