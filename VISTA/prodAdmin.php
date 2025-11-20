<?php
include_once "../configuracion.php";
$objSesion = new Session();
$objSesion->validarLogin(true);
include_once './structure/headerAdmin.php';
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos)) { ?>
                <?php foreach ($productos as $producto) { ?>
                    <tr>
                        <td><?= $producto->getIdProducto() ?></td>
                        <td><?= $producto->getNombre() ?></td>
                        <td><?= $producto->getDetalle() ?></td>
                        <td><?= $producto->getPrecio() ?></td>
                        <td><?= $producto->getStock() ?></td>
                        <td><?= $producto->getDescuento() ?></td>
                        <td>
                            <img src="../uploads/<?= $producto->getImagen() ?>"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        </td>

                        <td>
                            <button class="btn btn-warning btn-sm editar-btn" data-id="<?= $producto->getIdProducto() ?>">
                                Editar
                            </button>

                            <button class="btn btn-danger btn-sm eliminar-btn" data-id="<?= $producto->getIdProducto() ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php } ?>

            <?php } else { ?>
                <tr>
                    <td colspan="7" class="text-center">No hay productos cargados.</td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">
        ← Volver al menú anterior
    </a>
</div>

<script>
    function showToast(text, type = "success") {
        const toast = document.getElementById("toast");
        toast.className = `toast show ${type}`;
        toast.innerText = text;
        setTimeout(() => toast.className = "toast", 3000);
    }

    $(document).ready(function() {

        //editar producto
        $(".editar-btn").click(function() {
            let btn = $(this);
            let row = btn.closest("tr");
            let id = btn.data("id");

            // Convertir a inputs
            row.find("td:eq(1)").html(`<input class="form-control" value="${row.find("td:eq(1)").text()}">`);
            row.find("td:eq(2)").html(`<input class="form-control" value="${row.find("td:eq(2)").text()}">`);
            row.find("td:eq(3)").html(`<input class="form-control" type="number" value="${row.find("td:eq(3)").text().replace('$','')}">`);
            row.find("td:eq(4)").html(`<input class="form-control" type="number" value="${row.find("td:eq(4)").text()}">`);

            btn.removeClass("btn-warning").addClass("btn-success").text("Guardar");

            btn.off().click(function() {

                let pronombre = row.find("td:eq(1) input").val();
                let prodetalle = row.find("td:eq(2) input").val();
                let precio = row.find("td:eq(3) input").val();
                let procantstock = row.find("td:eq(4) input").val();

                $.ajax({
                    url: "action/actionEditarProd.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        idproducto: id,
                        pronombre: pronombre,
                        prodetalle: prodetalle,
                        precio: precio,
                        procantstock: procantstock
                    },
                    success: function(res) {
                        if (res.success) {

                            row.find("td:eq(1)").text(pronombre);
                            row.find("td:eq(2)").text(prodetalle);
                            row.find("td:eq(3)").text("$" + precio);
                            row.find("td:eq(4)").text(procantstock);

                            btn.removeClass("btn-success").addClass("btn-warning").text("Editar");
                            showToast("Producto actualizado.");
                        } else {
                            showToast(res.message, "error");
                        }
                    }
                });
            });
        });

        $(".eliminar-btn").click(function() {
            let id = $(this).data("id");
            let row = $(this).closest("tr");


            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {


                    $.ajax({
                        url: "action/actionEliminarProducto.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            id: id
                        },
                        success: function(res) {
                            if (res.success) {
                                row.remove();

                                Swal.fire('¡Eliminado!', 'El producto ha sido borrado.', 'success');
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        }
                    });
                }
            })
        });

    });
</script>