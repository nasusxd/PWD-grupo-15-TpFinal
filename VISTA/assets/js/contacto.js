$(document).ready(function () {
    $("#contactoForm").validate({
        rules: {
            nombre: { 
                required: true, 
                minlength: 10  
            },
            correo: {
                required: true,
                email: true 
            },
            asunto: {
                required: true,
                minlength: 8 
            },
            mensaje: {
                required: true,
                minlength: 10 
            }

        },
        messages: {
            nombre: {
                required: "Ingrese su nombre completo",
                minlength: "El nombre debe tener al menos 10 caracteres" 
            },
            correo: {
                required: "Ingrese su correo", 
                email: "Ingrese un email válido" 
            },
            asunto: {
                required: "Ingrese el asunto", 
                minlength: "El asunto debe tener al menos 8 caracteres" 
            },
            mensaje: {
                required: "Ingrese el mensaje para saber como lo podemos ayudar", 
                minlength: "El mensaje debe tener al menos 10 caracteres" 
            }
        },
        errorClass: "text-danger",
        errorElement: "div",
        submitHandler: function(form) {
            // Solo se ejecuta si el formulario es válido
            let datosFormulario = {
                nombre: $("#nombre").val(),
                correo: $("#correo").val(),
                asunto: $("#asunto").val(),
                mensaje: $("#mensaje").val()
            };

            $.ajax({
                url: "action/actionContacto.php",
                type: "POST",
                data: datosFormulario,
                dataType: "json",
                success: function(respuesta) {
                    if (respuesta.success) {
                        $("#datosContacto").html('<div class="alert alert-success">¡Mensaje enviado con éxito!</div>');
                    } else {
                        $("#datosContacto").html(`<div class="alert alert-danger">${respuesta.msg}</div>`);
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
    $("#contactoButton").click(function(){
        $("#contactoForm").submit();
    });
});
