<?php
include_once "../configuracion.php";
include_once './structure/headerAdmin.php';
$objCompra = new ABMCompra();
$objUsuario = new ABMUsuario();
$abmCompraEstado = new ABMCompraEstado();
$objCompraEstadoTipo = new ABMCompraEstadoTipo();
$listaCompras = $objCompra->buscar(null);
$listaUsuarios  = $objUsuario->buscar(null);

?>

<div class="container mt-4">
    <h2>Listado de Compras</h2>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID compra</th>
                <th>coFecha</th>
                <th>Email del Usuario</th>
                <th>Estado de la compra</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listaCompras)) { ?>
                <?php foreach ($listaCompras as $compra) { ?>
                    <tr><?php 
                    $usuario = $objUsuario->buscar(['idusuario' => $compra->getIdUsuario()]); //id usuario
                    $mailUsuario = $usuario[0]->getMail(); //obtengo el mail
                    $idCompra = $compra->getIdCompra();
                    $objCompraEstado = $abmCompraEstado->buscar(['idcompra' => $idCompra]);
                    $estadoCompra = $objCompraEstado[0]->getIdCompraEstadoTipo();
                    $compraEstadoTipo = $objCompraEstadoTipo->buscar(['idcompraestadotipo' => $estadoCompra]);
                    ?>
                        <td><?= $compra->getIdCompra() ?></td>
                        <td><?= $compra->getFecha() ?></td>
                        <td><?= $mailUsuario ?></td>
                        <td id="estadoCompra<?= $compra->getIdCompra() ?>">
                          <?= $compraEstadoTipo[0]->getDescripcion() ?>
                        </td>
                        <td>
                            <a href="detalleCompra.php?idcompra=<?= $compra->getIdCompra() ?>" class="btn btn-primary btn-sm">
                                Ver detalles
                            </a>
                            <button class="btn btn-info btn-sm btnCambiarEstado" data-id="<?= $compra->getIdCompra() ?>" data-estado="<?= $estadoCompra ?>">
                                Cambiar estado
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
        ← Volver al menu anterior
    </a>
</div>

<!-- modal -->
<div class="modal fade" id="modalEstadoCompra" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cambiar Estado de la Compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="idCompraModal">

        <label>Seleccionar nuevo estado:</label>
        <select id="nuevoEstado" class="form-select">
          <option value="2">Aceptada</option>
          <option value="3">Enviada</option>
          <option value="4">Cancelada</option>
        </select>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary" id="btnGuardarEstado">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<script src="./assets/js/cambiarEstadoCompra.js"></script>
<?php include_once "./structure/footer.php"; ?>

