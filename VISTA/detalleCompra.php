<?php 
include_once "../configuracion.php";
include_once './structure/headerAdmin.php';

$datos = datasubmitted();

$idCompra = $datos['idcompra'];

if ($idCompra <= 0) {
    echo "<div class='alert alert-danger'>ID de compra no válido.</div>";
    exit;
}

$objAbmCompraItem = new ABMCompraItem();
$objAbmProducto = new ABMProducto();

// busco los productos relacionados con la compra
$listadoItems = $objAbmCompraItem->buscar(['idcompra' => $idCompra]);

$precioTotal = 0;
?>
<div class="container mt-4">
  <h2>Detalle de la Compra #<?php $idCompra; ?></h2>

  <?php if (!empty($listadoItems)): ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio Unitario</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($listadoItems as $item): ?>
          <?php
          // info del producto
          $producto = $objAbmProducto->buscar(['idproducto' => $item->getIdproducto()]);
          if (!empty($producto)) {
            $producto = $producto[0];
            $nombreProducto = $producto->getPronombre();
            $precioUnitario = $producto->getPrecio();
            $cantidad = $item->getCicantidad();
            $subtotal = $precioUnitario * $cantidad;
            $precioTotal += $subtotal;
          } else {
            $nombreProducto = "Producto no encontrado";
            $precioUnitario = $cantidad = $subtotal = 0;
          }
          ?>
          <tr>
            <td><?php $nombreProducto; ?></td>
            <td><?php "$" . number_format($precioUnitario, 2); ?></td>
            <td><?php $cantidad; ?></td>
            <td><?php "$" . number_format($subtotal, 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-end">Total</th>
          <th><?php "$" . number_format($precioTotal, 2); ?></th>
        </tr>
      </tfoot>
    </table>
  <?php else: ?>
    <div class="alert alert-warning">No hay items para esta compra.</div>
  <?php endif; ?>

  <a href="compraAdmin.php" class="btn btn-secondary mt-3">Volver al Listado de Compras</a>
</div>
<?php 
include_once "../structure/footer.php";?>
