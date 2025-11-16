
$(document).ready(function () {
    // envio de ajax
    function enviarFormularioAjax(form) {
        $.ajax({
            url: "./action/actualizarUsuario.php",
            type: "POST",
            data: $(form).serialize(),
            dataType: "json",
            success: function (r) {
                if (r.success) {
                    alert(r.mensaje || "Cambios guardado s");
                } else {
                    alert("Error: " + (r.error || r.mensaje || "Error desconocido"));
                }
            },
            error: function (xhr, status, error) {
                alert("Error de conexión: " + error);
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
