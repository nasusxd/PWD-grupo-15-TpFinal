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
                    actualizarListaCarrito(respuesta.items);
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
                    // Vaciar el contenedor del carrito
                    let carritoContainer = $("#lista-carrito");
                    carritoContainer.empty();

                    // Reconstruir los productos
                    response.items.forEach(function(item) {
                        carritoContainer.append(`
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="carrito-item" data-id="${item.id}">
                                <div>
                                ${item.nombre}
                                <span class="badge bg-dark rounded-pill ms-2">${item.cantidad}</span>
                                </div>
                                <button class="btn btn-sm btn-danger eliminar-item" data-id="${item.id}">
                    <i class="bi bi-trash"></i>
                </button>
                            </div>
                            </li>
                        `);
                    });

                    // Actualizar total
                    $("#total-carrito").text(response.total);
                    $("#contador-carrito").text(response.total);
                }
            }
        });
    });


function actualizarListaCarrito(items) {

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

    $("#total-carrito").text(items.reduce((a, b) => a + b.cantidad, 0));
}

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

        // ALERTA CORRECTA (Bootstrap 5)
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



