$(document).ready(function () {

    let idCompraGlobal = null;

    $('.btnCambiarEstado').click(function () {

        idCompraGlobal = $(this).data('id');
        let estadoActual = $(this).data('estado');

        $("#idCompraModal").val(idCompraGlobal);
        $("#nuevoEstado").val(estadoActual);

        $("#modalEstadoCompra").modal("show");
    });

    $("#btnGuardarEstado").click(function () {
        let nuevoEstado = $("#nuevoEstado").val();

        $.ajax({
            url: "./action/actionActualizarCompraEstado.php",
            type: "POST",
            dataType: "json",
            data: {
                idcompra: idCompraGlobal,
                estado: nuevoEstado
            },
            success: function (data) {

                if (data.success) {

                    $("#estadoCompra" + idCompraGlobal).text(data.nuevoEstadoTexto);

                    $("#modalEstadoCompra").modal("hide");
                }
                else {
                    alert("Error: " + data.msg);
                }
            }
        });
    });

    $('.btnHistorial').click(function () {
        let idCompra = $(this).data("idcompra");

        $.ajax({
            url: "./action/actionObtenerHistorialEstados.php",
            type: "POST",
            dataType: "json",
            data: { idcompra: idCompra },
            success: function (resp) {

                if (!resp.success) {
                    alert(resp.msg);
                    return;
                }

                let rows = "";

                resp.data.forEach(e => {
                    rows += `
                        <tr>
                            <td>${e.descripcion}</td>
                            <td>${e.inicio}</td>
                            <td>${e.fin ?? "-"}</td>
                        </tr>
                    `;
                });

                $("#tablaHistorialEstados").html(rows);

                $("#modalHistorial").modal("show");
            }
        });
    });

});
