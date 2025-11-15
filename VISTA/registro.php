<?php include_once 'structure/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="width: 450px;">
        <div class="card-body">
            <h3 class="text-center mb-4" id="tituloFormulario">Registrarse</h3>

            <form id="registroForm" action="action/actionRegistro.php" method="POST">


                <div class="mb-3">
                    <input type="text" id="usnombre" class="form-control" name="usnombre" placeholder="Ingrese su nombre" required>
                </div>
                <div class="mb-3">
                    <input type="email" id="usmail" class="form-control" name="usmail" placeholder="Ingrese su email" required>
                </div>
                <div class="mb-3">
                    <input type="password" id="uspass" class="form-control" name="uspass" placeholder="Ingrese una contraseña" required>
                </div>

                <div id="mensaje" class="mb-3"></div>

                <button type="button" id="registerButton" class="btn btn-primary w-100">Registrarse</button>
            </form>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            $("#registerButton").click(function(e) {
                e.preventDefault();
                $("#mensaje").empty();

                let datosFormulario = {
                    usnombre: $("#usnombre").val(),
                    usmail: $("#usmail").val(),
                    uspass: $("#uspass").val(),
                };

                $.ajax({
                    type: 'POST',
                    url: 'action/actionRegistro.php',
                    data: datosFormulario,
                    dataType: 'json',
                    success: function(respuesta) {
                        console.log("dentro del success",respuesta); //si lo muestra
                        if (respuesta.success) {
                            //redirijo segun el rol
                            window.location.href = respuesta.redirect;
                        } else {
                            //muestro el error
                            $("#mensaje").html(`<div class="alert alert-danger">${respuesta.msg}</div>`);
                        }
                    },
                    error: function(xhr, status, error) {
                       console.log("Error AJAX:", xhr.responseText);
                        $("#mensaje").html('<div class="alert alert-danger">Error en la conexión al servidor.</div>');
                    }
                });
            });
        });
    </script>

<script src="js/registro.js"></script>

<?php include_once 'structure/footer.php'; ?>
