<?php
include_once 'structure/header.php';

$objSesion = new Session();
$carrito = $objSesion->getCarrito();
?>
<div class="container py-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Finalizar Compra</h2>

    <!-- Resumen del Carrito -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Resumen del pedido</strong>
        </div>
        <ul class="list-group list-group-flush" id="resumen-carrito">
             
        <?php
        $objAbmProducto = new AbmProducto();
        $total = 0;
        foreach ($carrito as $idProducto => $cantidad):  
            $producto = $objAbmProducto->buscar(['idproducto' => $idProducto]);
            if (count($producto) > 0) {
                $producto = $producto[0];
            }

            if ($producto) {
                $nombre = $producto->getNombre();
                $precio = $producto->getPrecio();
                $subtotal = $precio * $cantidad;
                $total += $subtotal;
            ?>
                <li class="list-group-item d-flex justify-content-between">
                    <?= $nombre ?> (x<?= $cantidad ?>)
                    <span>$<?= $subtotal ?></span>
                </li>
            <?php
            }
        endforeach;
        ?>
</ul>
        <div class="p-3 d-flex justify-content-between">
            <strong>Total:</strong>
            <strong id="total-final">$<?= $total ?></strong>
        </div>
    </div>
<!-- Fin Resumen Carrito -->


    <!-- Datos del comprador -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Tus datos</strong>
        </div>
        <div class="card-body">
            <form id="form-compra">
                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" name="direccion" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Método de pago</label>
                    <select class="form-select" name="pago" required>
                        <option value="tarjeta">Tarjeta de crédito</option>
                        <option value="debito">Tarjeta de débito</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia bancaria</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Confirmar compra
                </button>
            </form>
        </div>
    </div>

    <!-- Alerta -->
    <div id="alerta-compra"></div>
</div>


<?php
include_once 'structure/footer.php';
?>
