<?php
include_once "../configuracion.php";
include_once './structure/headerAdmin.php';
$objCompra = new ABMCompra();
$objUsuario = new ABMUsuario();
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listaCompras)) { ?>
                <?php foreach ($listaCompras as $compra) { ?>
                    <tr><?php 
                    $usuario = $objUsuario->buscar(['idusuario' => $compra->getIdUsuario()]); //id usuario
                    $mailUsuario = $usuario[0]->getMail(); //obtengo el mail
                        ?>
                        <td><?= $compra->getIdCompra() ?></td>
                        <td><?= $compra->getFecha() ?></td>
                        <td><?= $mailUsuario ?></td>
                        <td> <?php //mando el id de la compra al querer ver el detalle y el estado?>
                            <a href="detalleCompra.php?idcompra=<?= $compra->getIdCompra() ?>" class="btn btn-primary btn-sm">
                                Ver detalles
                            </a>
                            <a href="estadoCompra.php?idcompra=<?= $compra->getIdCompra() ?>" class="btn btn-info btn-sm">
                                Cambiar estado
                            </a>
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
