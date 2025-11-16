<?php 

//hay que usar una libreria de mail para el tema de cambiar el mail/contraseña/usuario
include_once 'structure/header.php'; 
$session = new Session();
$idUsuario = $session->getUsuario(); //saco la id y anda
$abmUsuario = new ABMUsuario(); //creo un abm usuario para buscar los datos del usuario
$objUsuario = $abmUsuario->buscar(['idusuario' => $idUsuario]); //obtengo un array de objs de usuarios
?>

<div class="container mt-5">
    <div class="card p-4">

        <h4 class="mb-4">Detalles básicos</h4>

        <form class="mb-4">
            <input type="hidden" name="accion" value="nombre">

            <label class="form-label">Nombre de usuario</label>
            <input type="text" name="usnombre" class="form-control" 
                   value="<?= $objUsuario[0]->getNombre() ?>">

            <div class="d-flex justify-content-end gap-2 mt-2">
                <button type="reset" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar cambios</button>
            </div>
        </form>

        <form class="mb-4">
            <input type="hidden" name="accion" value="email">

            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="usmail" class="form-control" 
                   value="<?= $objUsuario[0]->getMail() ?>">

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
        url: "./action/actualizarUsuario.php", 
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