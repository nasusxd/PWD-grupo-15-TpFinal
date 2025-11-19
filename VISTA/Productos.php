<?php
include_once './structure/header.php';
$sesion = new Session();

$idUsuario = $sesion->getUsuario();
$objAbmUsuarioRol = new ABMUsuarioRol();
$objUsuarioRol = $objAbmUsuarioRol->buscar(['idusuario' => $idUsuario]);

$objProducto = new ABMProducto();
$listaProductos = $objProducto->buscar(null);

?>
<br><hr>
<!-- PRODUCTOS -->
<div class="container">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
<?php 
foreach ($listaProductos as $producto):
    $disponible = $producto->getStock() > 0;
?>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="../img/<?= $producto->getImagen(); ?>" 
     class="card-img-top img-fluid <?= $disponible ? '' : 'img-blur' ?>" 
     style="height: 450px; object-fit: cover;" 
     alt="<?= $producto->getNombre() ?>">

        <div class="card-body d-flex flex-column">
          <?php if ($disponible): ?>
            <h5 class="card-title mb-2"><?= $producto->getNombre() ?></h5>
            <p class="mb-1">Cantidad disponible: <?= $producto->getStock() ?></p>
            <p class="card-text text-success fw-bold mb-3">$<?= $producto->getPrecio(); ?></p>

            <?php if ($objUsuarioRol[0]->getIdRol() == 1): ?>
              <button class="agregar-carrito btn btn-primary mt-auto" 
                      data-id="<?=$producto->getIdProducto();?>" 
                      data-nombre="<?=$producto->getNombre();?>">
                Agregar al carrito
              </button>
            <?php else: ?>
              <div class="mt-auto text-center w-100">
                <span class="fw-bold">Inicia sesión para comprar</span>
              </div>
            <?php endif; ?>

          <?php else: ?>
            <div class="text-center text-muted" style="opacity: 0.6;">
              <h5 class="card-title">No disponible</h5>
              <p class="mb-0"><?= $producto->getNombre() ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
<?php 
endforeach;

 ?>
  </div>
</div>

<script src="./assets/js/carrito.js"></script>
<link rel="stylesheet" href="./assets/css/carrito.css">

<style>
.img-blur {
    filter: blur(1px);
    opacity: 0.4;
}
</style>
<?php
include_once 'structure/footer.php';
?>
