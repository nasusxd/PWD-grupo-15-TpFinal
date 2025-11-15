<?php
include_once './structure/header.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card shadow-sm" style="width: 400px;">
            <div class="card-body">
                <h3 class="text-center mb-4">Iniciar Sesión</h3>
                <form name="loginForm" id="loginForm">
                    <div class="form-group">
                        <label for="email"></label>
                        <input type="email" id="usmail" class="form-control" placeholder="Ingrese el email" name="usmail" required>
                    </div>
                    <div class="form-group">
                        <label for="password"></label>
                        <input type="password" id="uspass" class="form-control" placeholder="Ingrese la contraseña" name="uspass" required>
                    </div>
                    <button type="button" id="loginButton" class="btn btn-primary btn-block mt-4">Iniciar Sesión</button>
                </form>
                <div id="mensaje" class="mt-2 text-center"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#loginButton").click(function(e) {
                e.preventDefault();

                let datosFormulario = {
                    usmail: $("#usmail").val(),
                    uspass: $("#uspass").val()
                };

                $.ajax({
                    url: "action/actionLogin.php",
                    type: "POST",
                    data: datosFormulario,
                    dataType: "json",
                    success: function(respuesta) {
                        if (respuesta.success) {
                            console.log("login exitoso");
                            //redirijo segun el rol
                            window.location.href = respuesta.redirect;
                        } else {
                            console.log("error en el login");
                            //muestro el error
                            $("#mensaje").html(`<div class="alert alert-danger">${respuesta.msg}</div>`);
                        }
                    },
                    error: function() {
                        $("#mensaje").html('<div class="alert alert-danger">Error en la conexión al servidor.</div>');
                    }
                });
            });
        });
    </script>

    <div class="text-center mt-3">
        <p>¿No tenés cuenta?</p>
        <a href="registro.php" class="btn btn-outline-primary w-45">Registrarse</a>
    </div>
</body>

</html>
<?php
include_once 'structure/footer.php';
?>