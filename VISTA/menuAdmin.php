<?php
include_once "../configuracion.php";
include_once './structure/headerAdmin.php';
$objMenu = new ABMMenu();
$listarMenus = $objMenu->buscar(null);
?>
<div class="container mt-4">
    <h2>Listado Menús</h2>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
    <div id="toast-container"></div>
</div>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID menú</th>
                <th>Nombre menú</th>
                <th>Menú descripción</th>
                <th>ID padre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listarMenus)) { ?>
                <?php foreach ($listarMenus as $menu) { ?>
                    <tr>
                        <td><?= $menu->getIdMenu() ?></td>
                        <td><?= $menu->getNombre() ?></td>
                        <td><?= $menu->getDescripcion() ?></td>
                        <td><?= $menu->getIdPadre() ?></td>
                        <td id="estado" class="estado"><?= $menu->getDeshabilitado() ? 'Deshabilitado' : 'Habilitado' ?></td>
                        <td>
            
                        <button class="btn btn-warning btn-sm btn-cambiar-habilitado" 
                        data-id="<?= $menu->getIdMenu() ?>" 
                        data-estado="0">Habilitar</button>
                        <button class="btn btn-danger btn-sm btn-cambiar-deshabilitado" 
                        data-id="<?= $menu->getIdMenu() ?>" 
                        data-estado="1">Deshabilitar</button>
                    </td>
                    </tr>
                <?php } ?>

            <?php } else { ?>
                <tr>
                    <td colspan="7" class="text-center">No hay menús cargados.</td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">
        ← Volver al menu anterior
    </a>
    <br><br>
</div>

<script>
$(document).ready(function() {

    // Función común para ambos botones
    $('.btn-cambiar-habilitado, .btn-cambiar-deshabilitado').click(function() {
        var boton = $(this);
        var idMenu = boton.data('id');
        var nuevoEstado = boton.data('estado'); // 0 = habilitar, 1 = deshabilitar
        var fila = boton.closest('tr');

        $.ajax({
            url: './action/actionMenu.php',
            type: 'POST',
            data: { idMenu: idMenu, deshabilitado: nuevoEstado },
            success: function(response) {
                if(response.success) {
                    // Actualizar texto del td de estado
                    fila.find('.estado').text(nuevoEstado == 1 ? 'Deshabilitado' : 'Habilitado');
                    // Cambiar visibilidad de botones
                    if(nuevoEstado == 1){
                        // Si deshabilitamos, mostrar botón "Habilitar" y ocultar "Deshabilitar"
                        fila.find('.btn-cambiar-habilitado').show();
                        fila.find('.btn-cambiar-deshabilitado').hide();
                    } else {
                        // Si habilitamos, mostrar botón "Deshabilitar" y ocultar "Habilitar"
                        fila.find('.btn-cambiar-habilitado').hide();
                        fila.find('.btn-cambiar-deshabilitado').show();
                    }

                } else {
                    var mensajeError = response.message && response.message.trim() !== '' 
                           ? response.message 
                           : 'No se pudo realizar la acción';
                           mostrarAlerta(mensajeError, 'danger', 5000);
                }
            },
            error: function() {
                alert('Error al actualizar el menú.');
            }
        });
    });

    // Inicializamos visibilidad según estado actual al cargar la página
    $('tr').each(function() {
        var fila = $(this);
        var estado = fila.find('.estado').text().trim();
        if(estado === 'Deshabilitado') {
            fila.find('.btn-cambiar-habilitado').show();
            fila.find('.btn-cambiar-deshabilitado').hide();
        } else {
            fila.find('.btn-cambiar-habilitado').hide();
            fila.find('.btn-cambiar-deshabilitado').show();
        }
    });

});

function mostrarAlerta(mensaje, tipo = 'success', duracion = 3000) {
    var toastId = 'toast-' + Date.now();
    var bgClass = tipo === 'success' ? 'bg-success' :
                  tipo === 'danger' ? 'bg-danger' :
                  tipo === 'warning' ? 'bg-warning' : 'bg-info';

    var toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${mensaje}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    $('#toast-container').append(toastHTML);

    var toastElement = new bootstrap.Toast(document.getElementById(toastId), { delay: duracion });
    toastElement.show();

    // Eliminar el toast del DOM cuando desaparezca
    $('#' + toastId).on('hidden.bs.toast', function () {
        $(this).remove();
    });
}

</script>
