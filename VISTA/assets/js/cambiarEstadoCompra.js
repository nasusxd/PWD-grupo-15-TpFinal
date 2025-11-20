$(document).ready(function() {

    let idCompraGlobal = null; 

    $('.btnCambiarEstado').click(function () {

        idCompraGlobal = $(this).data('id').toString().trim(); 

        let estado = $(this).data('estado');

        $("#idCompraModal").val(idCompraGlobal);
        $("#nuevoEstado").val(estado);

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

});
