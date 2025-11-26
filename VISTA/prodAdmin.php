<?php
include_once "../configuracion.php";
$objSesion = new Session();
$objSesion->validarLogin(true);

include_once './structure/header.php';

$objProductos = new ABMProducto();
$productos = $objProductos->buscar(null);
?>

<div class="container mt-4">
    <h2>Listado de Productos</h2>

    <a href="agregarProd.php" class="btn btn-primary mb-3">Agregar producto</a>

    <div id="toast" class="toast"></div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Detalle</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Descuento</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($productos)) { ?>
                <?php foreach ($productos as $producto) { 
                    $estado = $producto->getProDeshabilitado(); // 0 = habilitado, 1 = deshabilitado
                    $stock = $producto->getStock();

                    if ($estado == 1) {
                        $estadoTexto = "<span class='badge bg-danger'>DESHABILITADO</span>";
                    } elseif ($stock == 0) {
                        $estadoTexto = "<span class='badge bg-warning text-dark'>DESHABILITADO (sin stock)</span>";
                    } else {
                        $estadoTexto = "<span class='badge bg-success'>HABILITADO</span>";
                    }
                ?>
                    <tr>
                        <td><?= $producto->getIdProducto() ?></td>
                        <td><?= $producto->getNombre() ?></td>
                        <td><?= $producto->getDetalle() ?></td>
                        <td>$<?= $producto->getPrecio() ?></td>
                        <td><?= $producto->getStock() ?></td>
                        <td><?= $producto->getDescuento() ?>%</td>

                        <td>
                            <img src="../uploads/<?= $producto->getImagen() ?>"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        </td>

                        <td><?= $estadoTexto ?></td>

                        <td>
                            <button class="btn btn-warning btn-sm editar-btn" 
                                data-id="<?= $producto->getIdProducto() ?>">
                                Editar
                            </button>

                            <?php if ($estado == 0) { ?>
                                <button class="btn btn-danger btn-sm estado-btn"
                                    data-id="<?= $producto->getIdProducto() ?>"
                                    data-action="baja">
                                    Dar de baja
                                </button>
                            <?php } else { ?>
                                <button class="btn btn-success btn-sm estado-btn"
                                    data-id="<?= $producto->getIdProducto() ?>"
                                    data-action="alta">
                                    Dar de alta
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="9" class="text-center">No hay productos cargados.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary">← Volver al menú anterior</a>
</div>

<script>
    function showToast(text, type = "success") {
        const toast = document.getElementById("toast");
        toast.className = `toast show ${type}`;
        toast.innerText = text;
        setTimeout(() => toast.className = "toast", 3000);
    }

    $(document).ready(function () {

        /* ------------------------------------
           BOTÓN EDITAR → GUARDAR
        ------------------------------------ */
        $(".editar-btn").click(function () {
            let btn = $(this);
            let row = btn.closest("tr");
            let id = btn.data("id");

            if (btn.hasClass("btn-success")) {
                let pronombre = row.find("td:eq(1) input").val();
                let prodetalle = row.find("td:eq(2) input").val();
                let precio = row.find("td:eq(3) input").val();
                let procantstock = row.find("td:eq(4) input").val();
                let descuento = row.find("td:eq(5) input").val();

                $.ajax({
                    url: "action/actionEditarProd.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        idproducto: id,
                        pronombre,
                        prodetalle,
                        precio,
                        procantstock,
                        descuento
                    },
                    success: function (res) {
                        if (res.success) {
                            row.find("td:eq(1)").text(pronombre);
                            row.find("td:eq(2)").text(prodetalle);
                            row.find("td:eq(3)").text("$" + precio);
                            row.find("td:eq(4)").text(procantstock);
                            row.find("td:eq(5)").text(descuento + "%");

                            btn.removeClass("btn-success").addClass("btn-warning").text("Editar");
                            showToast("Producto actualizado.");
                        } else {
                            showToast(res.message, "error");
                        }
                    }
                });

            } else {
                row.find("td:eq(1)").html(`<input class="form-control" value="${row.find("td:eq(1)").text()}">`);
                row.find("td:eq(2)").html(`<input class="form-control" value="${row.find("td:eq(2)").text()}">`);
                row.find("td:eq(3)").html(`<input type="number" class="form-control" value="${row.find("td:eq(3)").text().replace('$','')}">`);
                row.find("td:eq(4)").html(`<input type="number" class="form-control" value="${row.find("td:eq(4)").text()}">`);
                row.find("td:eq(5)").html(`<input type="number" class="form-control" value="${row.find("td:eq(5)").text()}">`);

                btn.removeClass("btn-warning").addClass("btn-success").text("Guardar");
            }
        });

        /* ------------------------------------
           BOTÓN DAR DE BAJA / ALTA
        ------------------------------------ */
        $(".estado-btn").click(function () {
            let id = $(this).data("id");
            let action = $(this).data("action");
            let row = $(this).closest("tr");

            let texto = action === "baja" 
                ? "El producto será deshabilitado." 
                : "El producto será habilitado nuevamente.";

            Swal.fire({
                title: "¿Confirmar acción?",
                text: texto,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: action === "baja" ? "Dar de baja" : "Dar de alta",
                cancelButtonText: "Cancelar",
                confirmButtonColor: action === "baja" ? "#d33" : "#28a745"
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "action/actionEstadoProducto.php",
                        type: "POST",
                        dataType: "json",
                        data: { id, action },
                        success: function (res) {
                            if (res.success) {
                                Swal.fire("Listo", res.message, "success");
                                location.reload();
                            } else {
                                Swal.fire("Error", res.message, "error");
                            }
                        }
                    });
                }
            });
        });
    });
</script>
