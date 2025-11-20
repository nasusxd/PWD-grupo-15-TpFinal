<?php
include_once 'structure/header.php';
$objAbmRol = new ABMRol();
$listaRoles = $objAbmRol->buscar(null);
?>

<div class="container mt-5">

    <a href="javascript:history.back()" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Volver atrás
    </a>

    <h2 class="mb-4">Administrar Roles</h2>

    <div class="card mb-4 p-3">
        <h5>Agregar Nuevo Rol</h5>
        <form id="formNuevoRol">
            <div class="input-group">
                <input type="text" class="form-control" name="rodescripcion" placeholder="Nombre del Rol" required>
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

        <tbody id="tablaRoles">
            <?php foreach ($listaRoles as $rol): ?>
                <tr id="filaRol<?= $rol->getIdRol() ?>">
                    <td><?= $rol->getIdRol() ?></td>
                    <td><?= $rol->getRoDescripcion() ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2 flex-wrap">

                            <?php 
                                $menuRol = new MenuRol();
                                $menus = $menuRol->listar("idrol = " . $rol->getIdRol());

                                if (!empty($menus)) {
                                    foreach ($menus as $mr) {
                                        $menuObj = new abmMenu();
                                        $menuEncontrado = $menuObj->buscar(['idmenu' => $mr->getIdMenu()]);
                                        if (!empty($menuEncontrado)) {
                                            echo '<span class="badge bg-info text-dark">'
                                                . $menuEncontrado[0]->getNombre() .
                                                '</span>';
                                        }
                                    }
                                } else {
                                    echo '<span class="text-muted">Sin acceso</span>';
                                }
                            ?>

                            <?php if ($rol->getIdRol() > 2): ?>
                                <button class="btn btn-danger btn-sm ms-2" onclick="mostrarConfirmacionBorrar(<?= $rol->getIdRol() ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            <?php else: ?>
                                <span class="badge bg-secondary ms-2">Protegido</span>
                            <?php endif; ?>

                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal borrar -->
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
$(document).ready(function() {

    let rolAEliminar = null;

    // Mostrar modal borrar
    window.mostrarConfirmacionBorrar = function(id) {
        rolAEliminar = id;
        $('#modalConfirmarBorrar').modal('show');
    };

    // Confirmar borrar dinámico
    $('#btnConfirmarEliminar').click(function() {
        $.ajax({
            url: './action/actionRol.php',
            type: 'POST',
            data: { accion: 'borrar', idrol: rolAEliminar },
            dataType: 'json',
            success: function(data) {

                if (data.success) {

                    $('#filaRol' + rolAEliminar)
                        .css('background', '#ffb3b3')
                        .fadeOut(300, function() { $(this).remove(); });

                    $('#modalConfirmarBorrar').modal('hide');

                } else {
                    alert(data.mensaje);
                }
            }
        });
    });

    // Crear rol dinámico sin recargar
    $('#formNuevoRol').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: './action/actionRol.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {

                if (!data.success) {
                    alert(data.mensaje);
                    return;
                }

                let id = data.id;
                let desc = data.descripcion;

                let nuevaFila = `
                    <tr id="filaRol${id}">
                        <td>${id}</td>
                        <td>${desc}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="text-muted">Sin acceso</span>
                                <button class="btn btn-danger btn-sm ms-2" onclick="mostrarConfirmacionBorrar(${id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;

                $("#tablaRoles").append(nuevaFila);
                $("#formNuevoRol")[0].reset();
            }
        });
    });

});
</script>

<?php include_once 'structure/footer.php'; ?>
