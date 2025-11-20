<?php
include_once '../configuracion.php';

$objSesion = new Session();
$objSesion->validarLogin(false);
include_once 'structure/header.php';
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
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="mail" name="mail" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>

                <!-- Nombre del titular -->
                <div class="mb-3">
                    <label for="nombreTitular" class="form-label">Nombre del Titular</label>
                    <input type="text" class="form-control" id="nombreTitular" name="nombreTitular" required>
                </div>

                <!-- Número de Tarjeta -->
                <div class="mb-3">
                <label for="numeroTarjeta" class="form-label">Número de Tarjeta</label>
                <input type="text" class="form-control" id="numeroTarjeta" name="numeroTarjeta" maxlength="16" required>
               </div>
    
               <!-- Vencimiento -->
        <div class="row mb-3">
               <div class="row mb-3">
    <div class="col-6">
        <label for="mes" class="form-label">Mes</label>
        <select id="mes" name="mes" class="form-select" required>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select>
    </div>

    <div class="col-6">
        <label for="anio" class="form-label">Año</label>
        <select id="anio" name="anio" class="form-select" required>
            <?php
                for ($anio = 2010; $anio <=  2025; $anio++) {
                    echo "<option value='$anio'>$anio</option>";
                }
            ?>
        </select>
    </div>
</div>

    </div>
    <!-- Código de seguridad -->
    <div class="mb-3">
        <label for="cvv" class="form-label">Código de seguridad (CVV)</label>
        <input type="password" class="form-control" id="cvv" name="cvv" maxlength="4" required>
    </div>
     <button id="finalizar-compra" type="submit" class="btn btn-success w-100">Confirmar compra</button>
    </form>
<br>
    <!-- Alerta -->
    <div id="alerta-compra"></div>
</div>
</div>
</div>
<script src="<?= BASE_URL ?>./assets/js/finalizarCompra.js"></script>
<?php
include_once 'structure/footer.php';
?>
