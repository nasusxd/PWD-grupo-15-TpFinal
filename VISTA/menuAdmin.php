<?php
include_once "../configuracion.php";
$objSesion = new Session();
$objSesion->validarLogin(true);
include_once './structure/headerAdmin.php';

$objMenu = new ABMMenu();
$objMenuRol = new ABMMenuRol();
$objRol = new ABMRol();

$listarMenus = $objMenu->buscar(null);
$listaRoles = $objRol->buscar(null);
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
                <th>Descripción</th>
                <th>ID padre</th>
                <th>Estado</th>
                <th>Roles con acceso</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($listarMenus)) { ?>
                <?php foreach ($listarMenus as $menu) {
                    $idMenu = $menu->getIdMenu();
                    $rolesAsignados = $objMenuRol->buscar(["idmenu" => $idMenu]);
                ?>
                    <tr>
                        <td><?= $idMenu ?></td>
                        <td><?= $menu->getNombre() ?></td>
                        <td><?= $menu->getDescripcion() ?></td>
                        <td><?= $menu->getIdPadre() ?></td>

                        <td class="estado">
                            <?= $menu->getDeshabilitado() ? "Deshabilitado" : "Habilitado" ?>
                        </td>

                        <td>
                            <?php
                            if (!empty($rolesAsignados)) {
                                foreach ($rolesAsignados as $mr) {
                                    $rol = $objRol->buscar(["idrol" => $mr->getIdRol()]);
                                    if (!empty($rol)) {
                                        echo '<span class="badge bg-info me-1">' . $rol[0]->getRoDescripcion() . '</span>';
                                    }
                                }
                            } else {
                                echo "<span class='text-muted'>Sin roles</span>";
                            }
                            ?>
                        </td>

                        <td>
                            <!-- Botón para cambiar estado -->
                            <?php if ($idMenu != 15 && $idMenu != 16) { ?>
                                <button class="btn btn-warning btn-sm btn-cambiar-habilitado"
                                    data-id="<?= $idMenu ?>" data-estado="0">Habilitar</button>

                                <button class="btn btn-danger btn-sm btn-cambiar-deshabilitado"
                                    data-id="<?= $idMenu ?>" data-estado="1">Deshabilitar</button>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-sm" disabled>Sin Acción</button>
                            <?php } ?>

                            <!-- NUEVO: BOTÓN CONFIGURAR ROLES -->
                            <button class="btn btn-primary btn-sm btn-configurar-roles"
                                data-id="<?= $idMenu ?>"
                                data-nombre="<?= $menu->getNombre() ?>"
                                data-idpadre="<?= $menu->getIdPadre() ?>">
                                Roles
                            </button>

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

    <a href="index.php" class="btn btn-secondary">← Volver</a>
    <br><br>
</div>

<!-- 🔵 MODAL PARA CONFIGURAR ROLES ----------------------------- -->
<div class="modal fade" id="modalRoles" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Configurar roles para <span id="nombreMenu"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formRolesMenu">

                    <input type="hidden" name="idmenu" id="idmenu">

                    <?php foreach ($listaRoles as $rol) { ?>
                        <div class="form-check">
                            <input class="form-check-input rol-check" type="checkbox"
                                name="roles[]"
                                value="<?= $rol->getIdRol() ?>"
                                id="rol<?= $rol->getIdRol() ?>">
                            <label class="form-check-label" for="rol<?= $rol->getIdRol() ?>">
                                <?= $rol->getRoDescripcion() ?>
                            </label>
                        </div>
                    <?php } ?>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="btnGuardarRoles">Guardar</button>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        /* ==========================================================
           MOSTRAR MODAL Y CARGAR ROLES ASIGNADOS
        ========================================================== */
        $(".btn-configurar-roles").click(function() {
            let idmenu = $(this).data("id");
            let nombre = $(this).data("nombre");

            $("#idmenu").val(idmenu);
            $("#nombreMenu").text(nombre);
            $(".rol-check").prop("checked", false);

            $.ajax({
                url: "./action/actionGetRolesMenu.php",
                type: "POST",
                data: {
                    idmenu: idmenu
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        res.roles.forEach(r => $("#rol" + r).prop("checked", true));
                    }
                }
            });

            $("#modalRoles").modal("show");
        });


        /* ==========================================================
           GUARDAR ROLES (DINÁMICO)
        ========================================================== */
        $("#btnGuardarRoles").click(function() {

            $.ajax({
                url: "./action/actionMenu.php",
                type: "POST",
                data: $("#formRolesMenu").serialize(),
                dataType: "json",
                success: function(res) {

                    if (res.success) {

                        // 1️⃣ Cerrar modal
                        $("#modalRoles").modal("hide");

                        // 2️⃣ Mostrar toast
                        mostrarAlerta("Roles actualizados correctamente", "success");

                        // 3️⃣ Actualizar columna "Roles con acceso" sin recargar
                        let idMenu = $("#idmenu").val();

                        // Obtener checkbox seleccionados
                        let etiquetas = [];
                        $(".rol-check:checked").each(function() {
                            let label = $(this).next("label").text();
                            etiquetas.push(`<span class="badge bg-info me-1">${label}</span>`);
                        });

                        // Buscar la fila y reemplazar contenido
                        let fila = $("button.btn-configurar-roles[data-id='" + idMenu + "']").closest("tr");

                        if (etiquetas.length > 0) {
                            fila.find("td:nth-child(6)").html(etiquetas.join(" "));
                        } else {
                            fila.find("td:nth-child(6)").html("<span class='text-muted'>Sin roles</span>");
                        }

                    } else {
                        mostrarAlerta(res.msg, "danger");
                    }
                }
            });
        });


        /* ==========================================================
           HABILITAR / DESHABILITAR
        ========================================================== */
        $('.btn-cambiar-habilitado, .btn-cambiar-deshabilitado').click(function() {
            var boton = $(this);
            var idMenu = boton.data('id');
            var nuevoEstado = boton.data('estado');
            var fila = boton.closest('tr');

            $.ajax({
                url: './action/actionMenu.php',
                type: 'POST',
                data: {
                    idMenu: idMenu,
                    deshabilitado: nuevoEstado
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        fila.find('.estado').text(nuevoEstado == 1 ? 'Deshabilitado' : 'Habilitado');
                        mostrarAlerta("Estado actualizado", "info");
                    }
                }
            });
        });

    });


    /* ==========================================================
       Toast bonito
    ========================================================== */
    function mostrarAlerta(mensaje, tipo = 'success', tiempo = 3000) {
        var toast = document.createElement("div");
        toast.className = "toast align-items-center text-white bg-" + tipo;
        toast.role = "alert";
        toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${mensaje}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>`;
        document.getElementById("toast-container").appendChild(toast);
        new bootstrap.Toast(toast, {
            delay: tiempo
        }).show();
    }
    /* =========================================================================
   MARCAR AUTOMÁTICAMENTE EL PADRE CUANDO SE MARCA UN ROL DEL HIJO
=========================================================================== */
    $(".rol-check").change(function() {

        let idmenu = $("#idmenu").val(); // menú actual dentro del modal

        // Obtener ID del padre desde la tabla
        let idpadre = $("button.btn-configurar-roles[data-id='" + idmenu + "']")
            .data("idpadre");

        if (!idpadre || idpadre == 0) return; // si no tiene padre, cortar

        // Si se marcó el checkbox → marcarlo también en el padre
        if ($(this).is(":checked")) {

            let idrolSeleccionado = $(this).val();

            // 🔵 EN VEZ DE MARCAR EL MISMO ROL EN EL MISMO MENU…
            // 🔵 Vamos a pedir los roles del padre y marcarlo si corresponde

            $.ajax({
                url: "./action/actionGetRolesMenu.php",
                type: "POST",
                data: {
                    idmenu: idpadre
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {

                        // Si el rol NO está marcado en el padre → marcarlo
                        if (!res.roles.includes(parseInt(idrolSeleccionado))) {
                            $("#rol" + idrolSeleccionado).prop("checked", true);
                        }
                    }
                }
            });
        }
    });
</script>
<?php
include_once 'structure/footer.php';
?>