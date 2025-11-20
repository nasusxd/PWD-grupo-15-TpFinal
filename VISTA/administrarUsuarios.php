<?php
include_once "../configuracion.php";
$sesion = new Session();
$sesion->validarLogin(true);
include_once 'structure/headerAdmin.php';

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
                                            $rolesEncontrados = $abmRol->buscar(['idrol' => $idRol]);
                                            if (!empty($rolesEncontrados)) {
                                                $descRol = $rolesEncontrados[0]->getRoDescripcion();
                                                $rolesStr .= "<span class='badge bg-secondary me-1'>$descRol</span>";
                                            }
                                        }
                                        // ...

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
                                let textoConfirm = accion === 'deshabilitar' ?
                                    "El usuario no podrá acceder al sistema." :
                                    "El usuario volverá a tener acceso.";

                                Swal.fire({
                                    title: '¿Estás seguro?',
                                    text: textoConfirm,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Sí, confirmar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: 'action/actionEstadoUsuario.php',
                                            type: 'POST',
                                            data: {
                                                idusuario: id,
                                                accion: accion
                                            },
                                            dataType: 'json',
                                            success: function(res) {
                                                if (res.success) {
                                                    location.reload();
                                                } else {
                                                    Swal.fire('Error', res.msg, 'error');
                                                }
                                            },
                                            error: function() {
                                                Swal.fire('Error', 'Fallo de conexión', 'error');
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
                                        <option value="1">Cliente</option>
                                        <option value="2">Administrador</option>
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
                            $("#formNuevoUsuario").submit(function(e) {
                                e.preventDefault();


                                $("#mensaje").empty();


                                let formData = new FormData(this);

                                $.ajax({
                                    url: 'action/actionNuevoUsuario.php',
                                    type: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    dataType: 'json',
                                    success: function(res) {
                                        if (res.success) {

                                            $("#mensaje").html(`<div class="alert alert-success">${res.msg}</div>`);
                                            $("#formNuevoUsuario")[0].reset();
                                        } else {

                                            $("#mensaje").html(`<div class="alert alert-danger">${res.msg}</div>`);
                                        }
                                    },
                                    error: function() {
                                        $("#mensaje").html('<div class="alert alert-danger">Error de conexión.</div>');
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
                        echo "<div class='alert alert-danger'>ID de usuario invalido.</div>";
                        break;
                    }

                    $abmUsuario = new ABMUsuario();
                    $abmUsuarioRol = new ABMUsuarioRol();

                    $usuario = $abmUsuario->buscar(['idusuario' => $idEditar]);

                    if (!$usuario) {
                        echo "<div class='alert alert-danger'>Usuario no encontrado.</div>";
                        break;
                    }

                    $usuario = $usuario[0];
                    $nombre = $usuario->getNombre();
                    $mail = $usuario->getMail();

                    // Obtener rol actual
                    $relRol = $abmUsuarioRol->buscar(['idusuario' => $idEditar]);
                    $rolActual = $relRol ? $relRol[0]->getIdRol() : 1;
                ?>
                    <div class="container">
                        <h3 class="mb-4">Editar Usuario</h3>

                        <div class="d-flex justify-content-center">
                            <form id="formEditarUsuario" class="w-50">

                                <input type="hidden" name="idusuario" value="<?= $idEditar ?>">

                                <div class="mb-3">
                                    <label for="usnombre" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="usnombre" name="usnombre"
                                        value="<?= $nombre ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="usmail" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="usmail" name="usmail"
                                        value="<?= $mail ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="uspass" class="form-label">Nueva Contraseña (opcional)</label>
                                    <input type="password" class="form-control" id="uspass" name="uspass" value="">
                                    <small class="text-muted">Dejar vacío si NO querés cambiar la contraseña.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="idrol" class="form-label">Rol Asignado</label>

                                    <?php
                                  
                                    $idLogueado = $session->getUsuario();
                                  
                                    $esElMismo = ($idEditar == $idLogueado);
                                    ?>

                                    <select class="form-select" name="idrol" id="idrol" <?= $esElMismo ? 'disabled' : '' ?>>
                                        <option value="1" <?= $rolActual == 1 ? 'selected' : '' ?>>Cliente</option>
                                        <option value="2" <?= $rolActual == 2 ? 'selected' : '' ?>>Administrador</option>
                                    </select>

                                    <?php if ($esElMismo): ?>
                                        <input type="hidden" name="idrol" value="<?= $rolActual ?>">
                                        <small class="text-danger mt-1 d-block">
                                            <i class="bi bi-exclamation-triangle-fill"></i> No puedes cambiar tu propio rol de administrador.
                                        </small>
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
                        $(document).ready(function() {
                            $("#formEditarUsuario").submit(function(e) {
                                e.preventDefault();

                                let formData = new FormData(this);

                                $.ajax({
                                    url: 'action/actionEditarUsuario.php',
                                    type: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    dataType: 'json',
                                    success: function(res) {
                                        if (res.success) {
                                            $("#mensajeEditar").html(`<div class="alert alert-success">${res.msg}</div>`);

                                            setTimeout(() => {
                                                window.location.href = "administrarUsuarios.php?accion=listar";
                                            }, 1500);

                                        } else {
                                            $("#mensajeEditar").html(`<div class="alert alert-danger">${res.msg}</div>`);
                                        }
                                    },
                                    error: function() {
                                        $("#mensajeEditar").html('<div class="alert alert-danger">Error de conexión.</div>');
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
    <div class="d-flex justify-content-center mt-4 mb-4">
        <img src="../img/perrito.jpg" alt="Perro programando" class="img-fluid rounded" style="max-width: 300px;">
    </div>
    <div class="d-flex justify-content-center mt-4 mb-4">
        no haga macana
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
include_once 'structure/footer.php';
?>