<?php
include_once '../configuracion.php';
$sesion = new Session();

$sesion->validarLogin(true);

include_once './structure/header.php';

$objAbmMenu = new ABMMenu();
$objAbmMenuRol = new ABMMenuRol();

$rolesUsuario = $sesion->getRol();

function usuarioPuede($idMenu, $abmMenuRol, $rolesUsuario)
{
    $permisos = $abmMenuRol->buscar(['idmenu' => $idMenu]);

    foreach ($permisos as $perm) {
        if (in_array($perm->getIdRol(), $rolesUsuario)) {
            return true;
        }
    }
    return false;
}

$menusIndex = $objAbmMenu->buscar(['idpadre' => 1]);
?>

<div class="container mt-4 mb-4 flex-grow-1 d-flex flex-column justify-content-center">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <?php foreach ($menusIndex as $menuPadre): ?>

                <?php if (!usuarioPuede($menuPadre->getIdMenu(), $objAbmMenuRol, $rolesUsuario)) continue; ?>

                <?php $subMenus = $objAbmMenu->buscar(['idpadre' => $menuPadre->getIdMenu()]); ?>

                <div class="card mb-3 border-0 shadow-sm">

                    <button class="btn btn-primary w-100 p-3 fs-5 fw-bold text-start d-flex 
                        justify-content-between align-items-center"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse<?= $menuPadre->getIdMenu(); ?>">
                        <span><i class="bi bi-folder-fill me-2"></i> <?= $menuPadre->getNombre(); ?></span>
                        <i class="bi bi-chevron-down fs-6"></i>
                    </button>

                    <div class="collapse" id="collapse<?= $menuPadre->getIdMenu(); ?>">
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">

                                <?php foreach ($subMenus as $sub): ?>

                                    <?php if (!usuarioPuede($sub->getIdMenu(), $objAbmMenuRol, $rolesUsuario)) continue; ?>

                                    <?php
                                        
                                        $url = $sub->getDireccion();
                                        if ($url === null || trim($url) === "") {
                                            continue; 
                                        }
                                    ?>

                                    <a href="<?= $url; ?>"
                                        class="list-group-item list-group-item-action py-3 ps-4">
                                        <?= $sub->getNombre(); ?>
                                    </a>

                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
</div>

<?php include_once './structure/footer.php'; ?>
