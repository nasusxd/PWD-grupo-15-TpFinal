<?php
include_once 'structure/header.php';
$objAbmRol = new ABMRol();
$listaRoles = $objAbmRol->buscar(null);
?>

<div class="container mt-5">

    <!-- 🔙 BOTÓN VOLVER -->
    <a href="javascript:history.back()" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Volver atrás
    </a>

    <h2 class="mb-4">Administrar Roles</h2>

    <div class="card mb-4 p-3">
        <h5>Agregar Nuevo Rol</h5>
        <form id="formNuevoRol">
            <div class="input-group">
                <input type="text" class="form-control" name="rodescripcion" placeholder="Nombre del Rol (ej: Supervisor)" required>
                <input type="hidden" name="accion" value="nuevo">
                <button class="btn btn-success" type="submit">Crear</button>
            </div>
        </form>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaRoles as $rol): ?>
                <tr>
                    <td><?= $rol->getIdRol() ?></td>
                    <td><?= $rol->getRoDescripcion() ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="abrirPermisos(<?= $rol->getIdRol() ?>, '<?= $rol->getRoDescripcion() ?>')">
                            <i class="bi bi-key"></i> Permisos
                        </button>

                        <?php if ($rol->getIdRol() > 2): ?>
                            <button class="btn btn-danger btn-sm" onclick="mostrarConfirmacionBorrar(<?= $rol->getIdRol() ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php else: ?>
                            <span class="badge bg-secondary">Protegido</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div class="modal fade" id="modalPermisos" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestionar Acceso: <span id="nombreRolModal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formPermisos">
                    <input type="hidden" name="idrol" id="idRolPermiso">
                    <div id="listaCheckboxesMenus"></div>
                    <button type="submit" class="btn btn-primary mt-3 w-100">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalConfirmarBorrar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que querés eliminar este rol?
                <br><strong>Esta acción no se puede deshacer.</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmarEliminar" class="btn btn-danger">Eliminar Rol</button>
            </div>
        </div>
    </div>
</div>

<script>
    let rolAEliminar = null;

    function mostrarConfirmacionBorrar(id) {
        rolAEliminar = id;
        let modal = new bootstrap.Modal(document.getElementById('modalConfirmarBorrar'));
        modal.show();
    }

    document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
        $.post('./action/actionRol.php', {
            accion: 'borrar',
            idrol: rolAEliminar
        }, function(data) {
            var res = JSON.parse(data);
            alert(res.mensaje);
            location.reload();
        });
    });


    $('#formNuevoRol').submit(function(e) {
        e.preventDefault();
        $.post('./action/actionRol.php', $(this).serialize(), function(data) {
            location.reload();
        });
    });

    function abrirPermisos(idRol, nombreRol) {
        $('#idRolPermiso').val(idRol);
        $('#nombreRolModal').text(nombreRol);

        $.ajax({
            url: './action/actionObtenerMenusPorRol.php',
            type: 'POST',
            data: {
                idrol: idRol
            },
            success: function(response) {
                $('#listaCheckboxesMenus').html(response);
                var modal = new bootstrap.Modal(document.getElementById('modalPermisos'));
                modal.show();
            }
        });
    }

    $('#formPermisos').submit(function(e) {
    e.preventDefault();
    $.post('./action/actionActualizarPermisos.php', $(this).serialize(), function(data) {
        alert("Permisos actualizados");
        location.reload();
    });
});

</script>

<?php include_once 'structure/footer.php'; ?>