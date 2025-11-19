$(document).ready(function () {
    $(".agregar-carrito").off("click").on("click", function () {
        let idProducto = $(this).data("id");
        $.ajax({
            url: "./action/actionCarrito.php",
            type: "POST",
            data: { idproducto: idProducto, cantidad: 1 },
            dataType: "json",

            success: function (respuesta) {
                if (respuesta.success) {
                    $("#contador-carrito").text(respuesta.totalProductos);
                    pintarCarrito(respuesta.items);
                    $("#total-carrito").text("$" + respuesta.totalPrecio);

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

$(document).on("click", ".eliminar-item", function() {
    let idProducto = $(this).data("id");

        $.ajax({
            url: "./action/actionEliminarCarrito.php",
            type: "POST",
            data: { idproducto: idProducto },
            dataType: "json",
            success: function(response) {
                if(response.success) {
                    pintarCarrito(response.items);
                    $("#total-carrito").text("$" + response.total);
                    $("#contador-carrito").text(response.total);

                }
            }
        });
    });


// PARA LA COMPRA

$(document).ready(function () {
    $("#btn-finalizar").click(function () {
         // Eliminar alertas previas
        $(".modal-body .alert").remove();

        // Chequear si el carrito tiene items
        if ($("#lista-carrito li").length === 0) {
            const alerta = `
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                El carrito está vacío.
                
            </div>`;
            $(".modal-body").prepend(alerta);
            return;
        }

        const alerta = `
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            ¡Compra finalizada correctamente!
            
        </div>`;

        $(".modal-body").prepend(alerta);

        // Redirigir después de 2 segundos
        setTimeout(() => {
            window.location.href = "./finalizarCompra.php";
        }, 2000);
    });
});


$(document).ready(function () {
    $(".modalCarrito").off("click").on("click", function () {
        $(".modal-body .alert").remove();
        $.ajax({
            url: "./action/actionModal.php",
            type: "POST",
            dataType: "json",

            success: function (respuesta) {
                if (respuesta.success) {

                    pintarCarrito(respuesta.items);
                    $("#total-carrito").text("$" + respuesta.precioTotal);
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

function pintarCarrito(items) {
    $("#lista-carrito").html("");
    items.forEach(item => {
        $("#lista-carrito").append(`
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    ${item.nombre}
                    <span class="badge bg-dark rounded-pill ms-2">${item.cantidad}</span>
                </div>
                <button class="btn btn-sm btn-danger eliminar-item" data-id="${item.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </li>
        `);
    });
}

