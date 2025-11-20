<?php
include_once "../configuracion.php";
$sesion = new Session();
$sesion->validarLogin(true);
include_once './structure/header.php';


$objAbmRol = new ABMRol();
$listaRoles = $objAbmRol->buscar(null);
?>
<div class="container mt-5">
    <h1 class="text-center">Cargar Nuevo Menú</h1>
    <br>
    <div class="d-flex justify-content-center mt-4">
        <form id="formMenu" class="w-50" enctype="multipart/form-data">

        <div class="mb-0">
            <label for="pronombre">Nombre del Menú</label>
            <input type="text" id="pronombre"class="form-control" name="pronombre" required><br>
        </div>

        <div class="mb-0">
            <label for="prodetalle">Detalle del Menú:</label>
            <textarea id="prodetalle"class="form-control" name="prodetalle" required></textarea><br>
        </div>

        <div class="mb-0">
            <label for="medeshabilitado" class="form-label">Estado</label>
            <select id="medeshabilitado" name="medeshabilitado" class="form-select" required>
                <option value="0" selected>Habilitado</option>
                <option value="1">Deshabilitado</option>
            </select>
        </div><br>

        <div class="mb-0">
    <label class="form-label">Rol / Roles</label><br>
    <?php foreach ($listaRoles as $rol){ ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" 
                   type="checkbox" 
                   id="rol_<?php echo $rol->getIdRol(); ?>"
                   name="menuRoles[]" 
                   value="<?php echo $rol->getIdRol(); ?>">
            
            <label class="form-check-label" 
                   for="rol_<?php echo $rol->getIdRol(); ?>">
                <?php echo $rol->getIdRol().' '.$rol->getRoDescripcion(); ?>
            </label>
        </div>
    <?php } ?>
</div><br>

        <button type="submit" id="btn-cargarMenu" value="Cargar" class="btn btn-primary">Cargar Menú</button>
        <div id="mensaje" style="margin-top: 20px;"></div>
            <hr class="mt-4">
             <a href="./index.php" class="btn btn-danger">Volver</a>
        </form>

  </div>
</div>
<script>
$(document).ready(function() {
    $("#btn-cargarMenu").click(function(e) {
        e.preventDefault();
        $("#mensaje").empty();

        let formData = new FormData();

        // Datos del menú
        formData.append("pronombre", $("#pronombre").val());
        formData.append("prodetalle", $("#prodetalle").val());
        formData.append("medeshabilitado", $("#medeshabilitado").val());

        // Roles (checkbox)
        $('input[name="menuRoles[]"]:checked').each(function() {
            formData.append("menuRoles[]", $(this).val());
        });

        $.ajax({
            type: 'POST',
            url: './action/actionAgregarMenu.php',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(respuesta) {
                if (respuesta.success) {
                    $("#mensaje").html(`<div class="alert alert-success">Menú cargado correctamente.</div>`);
                } else {
                    $("#mensaje").html(`<div class="alert alert-danger">${respuesta.error}</div>`);
                }
            },
            error: function(err) {
                $("#mensaje").html(`<div class="alert alert-danger">Error en la petición AJAX.</div>`);
                console.log(err);
            }
        });
    });
});

</script>


<?php include_once "./structure/footer.php"; ?>