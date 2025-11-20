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
                <tr id="filaRol<?= $rol->getIdRol() ?>">
                    <td><?= $rol->getIdRol() ?></td>
                    <td><?= $rol->getRoDescripcion() ?></td>
                   <td>
    <div class="d-flex align-items-center gap-2 flex-wrap">

        <?php 
            // Cargar la clase MenuRol
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

        <!-- Botón borrar o protegido (fuera del bloque de menús) -->
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
$(document).ready(function() {
    let rolAEliminar = null;

    // MOSTRAR MODAL CONFIRMAR BORRAR
    window.mostrarConfirmacionBorrar = function(id) {
        rolAEliminar = id;
        $('#modalConfirmarBorrar').modal('show');
    };

    // CONFIRMAR ELIMINAR
    $('#btnConfirmarEliminar').click(function() {
        $.ajax({
            url: './action/actionRol.php',
            type: 'POST',
            dataType: 'json',
            data: { 
                accion: 'borrar', 
                idrol: rolAEliminar 
            },
            success: function(data) {
                if (data.success) {
                    $('#filaRol' + rolAEliminar).remove();
                    $('#modalConfirmarBorrar').modal('hide');
                }
            }
        });
    });

    // CREAR NUEVO ROL
    $('#formNuevoRol').submit(function(e) {
        e.preventDefault();
        
        let descripcion = $('input[name="rodescripcion"]').val();
        
        $.ajax({
            url: './action/actionRol.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                if (data.success) {
                    // Agregar nueva fila dinámicamente
                    let nuevaFila = `
                        <tr id="filaRol${data.idrol}">
                            <td>${data.idrol}</td>
                            <td>${descripcion}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="text-muted">Sin acceso</span>
                                    <button class="btn btn-danger btn-sm ms-2" onclick="mostrarConfirmacionBorrar(${data.idrol})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('table tbody').append(nuevaFila);
                    $('input[name="rodescripcion"]').val('');
                }
            }
        });
    });

    // ABRIR MODAL PERMISOS
    window.abrirPermisos = function(idRol, nombreRol) {
        $('#idRolPermiso').val(idRol);
        $('#nombreRolModal').text(nombreRol);

        $.ajax({
            url: './action/actionObtenerMenusPorRol.php',
            type: 'POST',
            data: { idrol: idRol },
            success: function(response) {
                $('#listaCheckboxesMenus').html(response);
                $('#modalPermisos').modal('show');
            }
        });
    };

    // GUARDAR PERMISOS
    $('#formPermisos').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: './action/actionActualizarPermisos.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                if (data.success) {
                    $('#modalPermisos').modal('hide');
                }
            }
        });
    });
});
</script>

<?php include_once 'structure/footer.php'; ?>