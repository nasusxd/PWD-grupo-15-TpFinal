
$(document).ready(function () {


    function enviarFormularioAjax(form) {


        $("#mensajeAlerta").html("");

        $.ajax({
            url: "./action/actualizarUsuario.php",
            type: "POST",
            data: $(form).serialize(),
            dataType: "json",
            success: function (r) {
                var claseAlerta = "";
                var mensaje = "";


                if (r.success) {
                    claseAlerta = "alert-success"; // Verde
                    mensaje = r.mensaje || "Cambios guardados con éxito";
                } else {
                    claseAlerta = "alert-danger"; // Rojo
                    mensaje = "Error: " + (r.error || r.mensaje || "Error desconocido");
                }


                var alertaHtml = '<div class="alert ' + claseAlerta + ' alert-dismissible fade show" role="alert">' +
                    mensaje +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';


                $("#mensajeAlerta").html(alertaHtml);
            },
            error: function (xhr, status, error) {

                var alertaHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    'Error de conexión: ' + error +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';

                $("#mensajeAlerta").html(alertaHtml);
            }
        });
        return false;
    }

    // validacion de nombre

    $("#formNombre").validate({
        rules: {
            usnombre: {
                required: true,
                minlength: 3
            }
        },
        messages: {
            usnombre: {
                required: "Este campo es obligatorio",
                minlength: "El nombre debe tener al menos 3 caracteres"
            }
        },
        errorClass: "text-danger",
        errorElement: "div",
        submitHandler: enviarFormularioAjax
    });

    // validacion de email

    $("#formEmail").validate({
        rules: {
            usmail: {
                required: true,
                email: true
            }
        },
        messages: {
            usmail: {
                required: "Este campo es obligatorio",
                email: "Por favor, ingrese un email válido"
            }
        },
        errorClass: "text-danger",
        errorElement: "div",
        submitHandler: enviarFormularioAjax
    });


    // validacion de contra

    $("#formPass").validate({
        rules: {
            pass1: {
                required: true,
                minlength: 8
            },
            pass2: {
                required: true,
                minlength: 8,
                equalTo: "#pass1"
            }
        },
        messages: {
            pass1: {
                required: "Este campo es obligatorio",
                minlength: "La contraseña debe tener al menos 8 caracteres"
            },
            pass2: {
                required: "Este campo es obligatorio",
                minlength: "La contraseña debe tener al menos 8 caracteres",
                equalTo: "Las contraseñas no coinciden"
            }
        },
        errorClass: "text-danger",
        errorElement: "div",
        submitHandler: enviarFormularioAjax
    });

    //para los botones de cancelar, restaura los value y saca las advertencias
    $('button[type="reset"]').click(function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        form[0].reset();
        form.validate().resetForm();
    });

});
