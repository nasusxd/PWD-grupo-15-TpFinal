$(document).ready(function () {
    $(".agregar-carrito").click(function () {

        let idProducto = $(this).data("id");

        $.ajax({
            url: "./action/actionCarrito.php",
            type: "POST",
            data: { idproducto: idProducto, cantidad: 1 },
            dataType: "json",

            success: function (respuesta) {
                if (respuesta.success) {
                    // Actualizar contador del carrito
                    $("#contador-carrito").text(respuesta.total);
                } else {
                    alert("Error: " + respuesta.mensaje);
                }
            },

            error: function () {
                alert("Hubo un problema con la petición AJAX.");
            }
        });

    });
});
