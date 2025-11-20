<?php
include_once "../configuracion.php";
$sesion = new Session();
$sesion->validarLogin(true);
include_once 'structure/header.php';

$objSesion = new Session();
$objSesion->validarLogin(null, 12); 


$session = new Session();
$tienePermiso = false;
$mensajeError = "";


if ($session->activa()) {
    if ($session->esAdmin()) {
        $tienePermiso = true;
    } else {
        $mensajeError = "No tienes permisos de administrador para ver esta sección.";
    }
} else {
    $mensajeError = "Debes iniciar sesión para acceder.";
}

if (!$tienePermiso) {
    echo "<div class='container mt-5'><div class='alert alert-danger text-center'>$mensajeError</div></div>";
    echo '<div class="container text-center"><a href="login.php" class="btn btn-secondary">Volver al Login</a></div>';
    include_once 'structure/footer.php';
    exit;
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Administración de Usuarios
        </div>
        <div class="card-body">

<?php
switch ($accion) {




case 'listar':

    $abmUsuario = new ABMUsuario();
    $listaUsuarios = $abmUsuario->buscar(null);
    $abmUsuarioRol = new ABMUsuarioRol();
    $abmRol = new ABMRol();
?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Listado de Usuarios</h3>
        <a href="administrarUsuarios.php?accion=nuevo" class="btn btn-success">
            <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>

<?php
$idLogueado = $session->getUsuario();

if (count($listaUsuarios) > 0) {
    foreach ($listaUsuarios as $objUsuario) {

        $idUsuario = $objUsuario->getIdUsuario();
        $nombre = $objUsuario->getNombre();
        $mail = $objUsuario->getMail();
        $deshabilitado = $objUsuario->getDeshabilitado();

        $listaRelacion = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);
        $rolesStr = "";

        foreach ($listaRelacion as $relacion) {
            $idRol = $relacion->getIdRol();
            $roles = $abmRol->buscar(['idrol' => $idRol]);

            if (!empty($roles)) {
                $rolesStr .= "<span class='badge bg-secondary me-1'>" . $roles[0]->getRoDescripcion() . "</span>";
            }
        }

        $estaHabilitado = ($deshabilitado == null || $deshabilitado == '0000-00-00 00:00:00');
?>
                <tr>
                    <td><?= $idUsuario ?></td>
                    <td><?= $nombre ?></td>
                    <td><?= $mail ?></td>
                    <td><?= $rolesStr ?></td>
                    <td>
                        <?php if ($estaHabilitado): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Baja</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">

                        <a href="administrarUsuarios.php?accion=editar&id=<?= $idUsuario ?>"
                           class="btn btn-warning btn-sm" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <?php if ($idUsuario == $idLogueado): ?>
                            <button class="btn btn-secondary btn-sm" disabled title="Usuario Actual">
                                <i class="bi bi-person-fill-lock"></i>
                            </button>
                        <?php else: ?>

                            <?php if ($estaHabilitado): ?>
                                <button class="btn btn-danger btn-sm btn-cambiar-estado"
                                        data-id="<?= $idUsuario ?>"
                                        data-accion="deshabilitar"
                                        title="Deshabilitar">
                                    <i class="bi bi-person-x-fill"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn btn-success btn-sm btn-cambiar-estado"
                                        data-id="<?= $idUsuario ?>"
                                        data-accion="habilitar"
                                        title="Habilitar">
                                    <i class="bi bi-person-check-fill"></i>
                                </button>
                            <?php endif; ?>

                        <?php endif; ?>

                    </td>
                </tr>

<?php
    }
} else {
    echo '<tr><td colspan="6" class="text-center">No hay usuarios cargados.</td></tr>';
}
?>

            </tbody>
        </table>
    </div>

<script>
$(document).ready(function() {

    $(".btn-cambiar-estado").click(function() {

        let id = $(this).data("id");
        let accion = $(this).data("accion");

        let texto = accion === "deshabilitar"
            ? "El usuario no podrá acceder al sistema."
            : "El usuario volverá a tener acceso.";

        Swal.fire({
            title: "¿Confirmar?",
            text: texto,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, continuar",
            cancelButtonText: "Cancelar"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "action/actionEstadoUsuario.php",
                    type: "POST",
                    data: { idusuario: id, accion: accion },
                    dataType: "json",

                    success: function(res){
                        if(res.success){
                            location.reload();
                        } else {
                            Swal.fire("Error", res.msg, "error");
                        }
                    }
                });

            }

        });

    });

});
</script>

<?php
break;




case 'nuevo':

    $abmRol = new ABMRol();
    $listaRoles = $abmRol->buscar(null);
?>

<div class="container">
    <h3 class="mb-4">Crear Nuevo Usuario</h3>

    <div class="d-flex justify-content-center">
        <form id="formNuevoUsuario" class="w-50">

            <div class="mb-3">
                <label for="usnombre" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="usnombre" name="usnombre" required>
            </div>

            <div class="mb-3">
                <label for="usmail" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="usmail" name="usmail" required>
            </div>

            <div class="mb-3">
                <label for="uspass" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="uspass" name="uspass" required>
            </div>

            <div class="mb-3">
                <label for="idrol" class="form-label">Rol Asignado</label>
                <select class="form-select" name="idrol" id="idrol">

                    <?php foreach ($listaRoles as $rol): ?>
                        <option value="<?= $rol->getIdRol() ?>">
                            <?= $rol->getRoDescripcion() ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Crear Usuario</button>
            </div>

            <div id="mensaje" class="mt-3"></div>

            <hr>
            <a href="administrarUsuarios.php?accion=listar" class="btn btn-secondary">Volver al listado</a>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {

    $("#formNuevoUsuario").submit(function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "action/actionNuevoUsuario.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function(res){
                if(res.success){
                    $("#mensaje").html(`<div class="alert alert-success">${res.msg}</div>`);
                    $("#formNuevoUsuario")[0].reset();
                } else {
                    $("#mensaje").html(`<div class="alert alert-danger">${res.msg}</div>`);
                }
            }
        });

    });

});
</script>

<?php
break;


case 'editar':

$idEditar = isset($_GET['id']) ? $_GET['id'] : null;

if (!$idEditar) {
    echo "<div class='alert alert-danger'>ID inválido.</div>";
    break;
}

$abmUsuario = new ABMUsuario();
$abmUsuarioRol = new ABMUsuarioRol();
$abmRol = new ABMRol();

$usuario = $abmUsuario->buscar(['idusuario' => $idEditar]);

if (!$usuario) {
    echo "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    break;
}

$usuario = $usuario[0];
$nombre = $usuario->getNombre();
$mail = $usuario->getMail();

$relRol = $abmUsuarioRol->buscar(['idusuario' => $idEditar]);
$rolActual = $relRol ? $relRol[0]->getIdRol() : 1;

$listaRoles = $abmRol->buscar(null);

$idLogueado = $session->getUsuario();
$esElMismo = ($idEditar == $idLogueado);
?>

<div class="container">
    <h3 class="mb-4">Editar Usuario</h3>

    <div class="d-flex justify-content-center">
        <form id="formEditarUsuario" class="w-50">

            <input type="hidden" name="idusuario" value="<?= $idEditar ?>">

            <div class="mb-3">
                <label for="usnombre" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" name="usnombre" value="<?= $nombre ?>" required>
            </div>

            <div class="mb-3">
                <label for="usmail" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" name="usmail" value="<?= $mail ?>" required>
            </div>

            <div class="mb-3">
                <label for="uspass" class="form-label">Nueva Contraseña (opcional)</label>
                <input type="password" class="form-control" name="uspass">
            </div>

            <div class="mb-3">
                <label for="idrol" class="form-label">Rol Asignado</label>

                <select class="form-select" name="idrol" id="idrol" <?= $esElMismo ? "disabled" : "" ?>>

                    <?php foreach ($listaRoles as $rol): ?>
                        <option value="<?= $rol->getIdRol() ?>" 
                                <?= $rolActual == $rol->getIdRol() ? "selected" : "" ?>>
                            <?= $rol->getRoDescripcion() ?>
                        </option>
                    <?php endforeach; ?>

                </select>

                <?php if ($esElMismo): ?>
                    <input type="hidden" name="idrol" value="<?= $rolActual ?>">
                    <small class="text-danger d-block">No puedes cambiar tu propio rol.</small>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Guardar Cambios</button>
            </div>

            <div id="mensajeEditar" class="mt-3"></div>

            <hr>
            <a href="administrarUsuarios.php?accion=listar" class="btn btn-secondary">Volver al listado</a>
        </form>
    </div>

</div>

<script>
$(document).ready(function(){

    $("#formEditarUsuario").submit(function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "action/actionEditarUsuario.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",

            success: function(res){
                if(res.success){
                    $("#mensajeEditar").html(`<div class="alert alert-success">${res.msg}</div>`);

                    setTimeout(()=> {
                        window.location.href = "administrarUsuarios.php?accion=listar";
                    }, 1500);

                } else {
                    $("#mensajeEditar").html(`<div class="alert alert-danger">${res.msg}</div>`);
                }
            }

        });

    });

});
</script>


<?php
break;
}
?>

</div>
</div>


<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al Panel</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include_once 'structure/footer.php'; ?>
