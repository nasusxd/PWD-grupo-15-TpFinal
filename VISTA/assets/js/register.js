$(document).ready(function () {
    $("#registroForm").validate({
        rules: {
            usnombre: {
                required: true,
                minlength: 3
            },
            usmail: {
                required: true,
                email: true
            },
            uspass: {
                required: true,
                minlength: 8
            }
        },
        messages: {
            usnombre: {
                required: "Este campo es obligatorio",
                minlength: "El nombre debe tener al menos 3 caracteres"
            },
            usmail: {
                required: "Este campo es obligatorio",
                email: "Ingrese un email válido"
            },
            uspass: {
                required: "Este campo es obligatorio",
                minlength: "La contraseña debe tener al menos 8 caracteres"
            }
        },
        errorClass: "text-danger",
        errorElement: "div",
        submitHandler: function(form) {
            // Solo se ejecuta si el formulario es válido
            let datosFormulario = {
                usnombre: $("#usnombre").val(),
                usmail: $("#usmail").val(),
                uspass: $("#uspass").val()
            };

            $.ajax({
                type: 'POST',
                url: 'action/actionRegistro.php',
                data: datosFormulario,
                dataType: 'json',
                success: function(respuesta) {
                    if (respuesta.success) {
                        window.location.href = respuesta.redirect;
                    } else {
                        $("#mensaje").html(`<div class="alert alert-danger">${respuesta.msg}</div>`);
                    }
                },
                error: function(xhr, status, error) {
                    $("#mensaje").html('<div class="alert alert-danger">Error en la conexión al servidor.</div>');
                }
            });

            return false; // evita submit normal
        }
    });

    // Vincular el botón al submit del formulario
    $("#registerButton").click(function() {
        $("#registroForm").submit();
    });
});
