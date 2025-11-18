$(document).ready(function () {
    $("#mail").on("input", function () {
        $("#alerta-compra").html("");
    });

    $("#form-compra").validate({
        rules: {
            nombre: { required: true },
            direccion: { required: true },
            mail: { required: true, email: true },
            nombreTitular: { required: true },
            numeroTarjeta: {
                required: true,
                digits: true,
                minlength: 16,
                maxlength: 16
            },
            mes: { required: true },
            anio: { required: true },
            cvv: {
                required: true,
                digits: true,
                minlength: 3,
                maxlength: 4
            }
        },

        messages: {
            nombre: { required: "Ingrese su nombre." },
            mail: {
                required: "Ingrese un email.",
                email: "Ingrese un email válido."
            },
            direccion: { required: "Ingrese su direccion." },
            nombreTitular: { required: "Ingrese el nombre del titular." },
            numeroTarjeta: {
                required: "Ingrese el número de la tarjeta.",
                digits: "Solo números.",
                minlength: "Debe tener 16 dígitos.",
                maxlength: "Debe tener 16 dígitos."
            },
            cvv: {
                required: "Ingrese el código de seguridad",
                digits: "Solo números.",
                minlength: "Debe tener 3 o 4 dígitos.",
                maxlength: "Debe tener 3 o 4 dígitos."
            }
        },

        errorClass: "text-danger",
        errorElement: "div",

        submitHandler: function(form) {
            let mail = $("#mail").val();

            $.ajax({
                url: "./action/actionVerificarCompra.php",
                type: "POST",
                data: { mail: mail },
                dataType: "json",
                success: function(resp) {
                    if (resp.existe) {
                        $("#alerta-compra").html(`
                            <div class="alert alert-success">
                                Compra realizada con éxito. Se envió el resumen a tu correo.
                            </div>
                        `);
        form.submit();
                    } else {
                        $("#alerta-compra").html(`
                            <div class="alert alert-danger">El correo no está registrado.</div>
                        `);
                    }
                }
            });
        }
    });

});
