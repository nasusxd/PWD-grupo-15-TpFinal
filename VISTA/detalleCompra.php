<?php 
include_once "../configuracion.php";
$sesion = new Session();
$sesion->validarLogin(true);
include_once './structure/header.php';

$datos = datasubmitted();
$idCompra = $datos['idcompra'];
if ($idCompra <= 0) {
    echo "<div class='alert alert-danger'>ID de compra no válido.</div>";
    exit;
}

$objAbmCompraItem = new ABMCompraItem();
$objAbmProducto = new ABMProducto();

// busco los productos relacionados con la compra
$listadoItems = $objAbmCompraItem->buscar(['idcompra' => $idCompra]); //se manda el id correctamente 
$precioTotal = 0;
?>
<div class="container mt-4">
  <h2>Detalle de la Compra #<?= $idCompra ?></h2>

  <?php if (!empty($listadoItems)): ?>
    <table class="table table-hover">
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
          //echo "<pre>"; print_r($producto[0]->getNombre()); echo "</pre>";

          if (!empty($producto)) {
            $producto = $producto[0];
            $nombreProducto = $producto->getNombre();
            $precioUnitario = $producto->getPrecio();
            $cantidad = $item->getCantidad();
            $subtotal = $precioUnitario * $cantidad;
            $precioTotal += $subtotal;
          } else {
            $nombreProducto = "Producto no encontrado";
            $precioUnitario = $cantidad = $subtotal = 0;
          }
          ?>
          <tr>
            <td><?= $nombreProducto; ?></td>
            <td><?= "$" . number_format($precioUnitario, 2); ?></td>
            <td><?= $cantidad; ?></td>
            <td><?= "$" . number_format($subtotal, 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-end">Total</th>
          <th><?= "$" . number_format($precioTotal, 2); ?></th>
        </tr>
      </tfoot>
    </table>
  <?php else: ?>
    <div class="alert alert-warning">No hay items para esta compra.</div>
  <?php endif; ?>

  <a href="compraAdmin.php" class="btn btn-secondary mt-3">Volver al Listado de Compras</a>
</div>
<?php 
include_once "./structure/footer.php";?>
