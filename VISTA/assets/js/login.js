$(document).ready(function () {
    $("#loginForm").validate({
        rules: {
            usmail: { required: true, email: true },
            uspass: { required: true, minlength: 8 }
        },
        messages: {
            usmail: { required: "Ingrese su email", email: "Ingrese un email válido" },
            uspass: { required: "Ingrese su contraseña", minlength: "La contraseña debe tener al menos 8 caracteres" }
        },
        errorClass: "text-danger",
        errorElement: "div",
        submitHandler: function(form) {
            // Solo se ejecuta si el formulario es válido
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
                        window.location.href = respuesta.redirect;
                    } else {
                        $("#mensaje").html(`<div class="alert alert-danger">${respuesta.msg}</div>`);
                    }
                },
                error: function() {
                    $("#mensaje").html('<div class="alert alert-danger">Error en la conexión al servidor.</div>');
                }
            });

            return false; // evita submit normal
        }
    });

    // Vincular el botón al submit del formulario
    $("#loginButton").click(function(){
        $("#loginForm").submit();
    });
});
