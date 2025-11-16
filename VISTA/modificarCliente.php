<?php 
include_once 'structure/header.php'; 
$session = new Session();
$usuario=$session->getUsuario();
?>

<div class="container mt-5">
    <div class="card p-4">

        <h4 class="mb-4">Detalles básicos</h4>

        <form class="mb-4">
            <input type="hidden" name="accion" value="nombre">

            <label class="form-label">Nombre de usuario</label>
            <input type="text" name="usnombre" class="form-control" 
                   value="<?= $usuario['usnombre'] ?>">

            <div class="d-flex justify-content-end gap-2 mt-2">
                <button type="reset" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar cambios</button>
            </div>
        </form>

        <form class="mb-4">
            <input type="hidden" name="accion" value="email">

            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="usmail" class="form-control" 
                   value="<?= $usuario['usmail'] ?>">

            <div class="d-flex justify-content-end gap-2 mt-2">
                <button type="reset" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar cambios</button>
            </div>
        </form>

        <form class="mb-4">
            <input type="hidden" name="accion" value="pass">

            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="pass1" class="form-control" >

            <label class="form-label mt-3">Repetir contraseña</label>
            <input type="password" name="pass2" class="form-control">

            <div class="d-flex justify-content-end gap-2 mt-2">
                <button type="reset" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar cambios</button>
            </div>
        </form>

    </div>

    <div class="mt-3">
        <button class="btn btn-secondary" onclick="history.back()">
            ← Volver atrás
        </button>
    </div>
</div>
<script>
    $("form").submit(function(e){
    e.preventDefault();

  
    $.ajax({
        url: "accion/actualizarUsuario.php", 
        type: "POST",
        data: $(this).serialize(),
        dataType: "json", 
        success: function(r){
            
            if(r.success){
                alert(r.mensaje || "Cambios guardados"); 
            } else {
                alert("Error: " + (r.error || r.mensaje || "Error desconocido")); 
            }
        },
        error: function(xhr, status, error){
            
            alert("Error de conexión: " + error);
        }
    });
});

</script>
<?php 
include_once 'structure/footer.php'; 
?>