<?php
include_once(__DIR__ . '/../configuracion.php');
$sesion = new Session();
$sesion->validarLogin(false);
include_once './structure/header.php';


$objAbmMenu = new ABMMenu();
$menuTodosProductos = $objAbmMenu->buscar(['idmenu' => 3]);
$estadoMenu = $menuTodosProductos[0]->getDeshabilitado();
$idUsuario = $sesion->getUsuario();
$objAbmUsuarioRol = new ABMUsuarioRol();
$objUsuarioRol = $objAbmUsuarioRol->buscar(['idusuario' => $idUsuario]);

$objProducto = new ABMProducto();
$listaProductos = $objProducto->buscar(null);
if ($estadoMenu == 1) {
    echo "<div class='alert alert-warning text-center' role='alert'>
            Los productos están deshabilitados.
          </div>";
}else{
?>
<br><hr>
<div class="container">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
<?php 
foreach ($listaProductos as $producto):
    $disponible = $producto->getStock() > 0;
    $esOferta = $producto->getDescuento() > 0;
?>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <?php if ($esOferta): ?>
    <div class="sticker-oferta">OFERTA</div>
<?php endif; ?>
        <img src="../uploads/<?= $producto->getImagen(); ?>" 
     class="card-img-top img-fluid <?= $disponible ? '' : 'img-blur' ?>" 
     style="height: 450px; object-fit: cover;" 
     alt="<?= $producto->getNombre() ?>">

        <div class="card-body d-flex flex-column">
          <?php if ($disponible): ?>
            <h5 class="card-title mb-2"><?= $producto->getNombre() ?></h5>
            <p class="mb-1">Cantidad disponible: <?= $producto->getStock() ?></p>
            <?php if (!$esOferta){ ?>
            <p class="card-text text-success fw-bold mb-3">$<?= $producto->getPrecio(); ?></p>
            <?php }else{ ?>
              <p class="card-text text-success fw-bold mb-0">Antes: $<?= $producto->getPrecio(); ?></p>
            <p class="text-danger fw-bold mb-1">
            Oferta: $<?= $producto->getPrecio() * (1 - $producto->getDescuento() / 100); ?>
          </p>
              <?php } ?>
            <?php if ($objUsuarioRol[0]->getIdRol() == 1): ?>
              <button class="agregar-carrito btn btn-primary mt-auto" 
                      data-id="<?=$producto->getIdProducto();?>" 
                      data-nombre="<?=$producto->getNombre();?>"
                      data-descuento="<?=$producto->getDescuento();?>">
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
<?php } ?>
<script src="./assets/js/carrito.js"></script>

<link rel="stylesheet" href="./assets/css/ofertas.css">
<style>
.img-blur {
    filter: blur(1px);
    opacity: 0.4;
}
</style>
<?php
include_once 'structure/footer.php';
?>
