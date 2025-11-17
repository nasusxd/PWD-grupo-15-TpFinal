<?php
include_once './structure/header.php';
$sesion = new Session();
//aca se evalua si el usuario esta logeado o no, si esta logeado al hacer click en su perfil habria q cambiarlo de vista poara q peuda modificarlo, si no esta log tendria q aparecer algo de iniciar sesion
if ($sesion->validar()) {
  $idUsuario = $sesion->getUsuario();
}
$objProducto = new ABMProducto();
$listaProductos = $objProducto->buscar(null);
?>
<br>
<div id="miCarrusel" class="carousel slide" data-bs-ride="carousel">
  
  <!-- Indicadores (la "barrita") -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#miCarrusel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Imagen 1"></button>
    <button type="button" data-bs-target="#miCarrusel" data-bs-slide-to="1" aria-label="Imagen 2"></button>
    <button type="button" data-bs-target="#miCarrusel" data-bs-slide-to="2" aria-label="Imagen 3"></button>
  </div>

  <!-- Imágenes -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="#" class="d-block w-100" alt="Imagen 1">
    </div>
    <div class="carousel-item">
      <img src="#" class="d-block w-100" alt="Imagen 2">
    </div>
    <div class="carousel-item">
      <img src="#" class="d-block w-100" alt="Imagen 3">
    </div>
  </div>

  <!-- Botón anterior -->
  <button class="carousel-control-prev" type="button" data-bs-target="#miCarrusel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>

  <!-- Botón siguiente -->
  <button class="carousel-control-next" type="button" data-bs-target="#miCarrusel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Siguiente</span>
  </button>
</div>
<br><hr>

<!-- PRODUCTOS -->
<div class="container">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
<?php 
foreach ($listaProductos as $producto) {
  if ($producto->getStock() > 0) {
?>
    <div class="col">
      <div class="card h-100">
        <img height="400" width="50" src="<?= $producto->getImagen(); ?>" class="card-img-top" alt="Producto 1">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= $producto->getNombre() ?></h5>
          <p>Cantidad disponible: <?= $producto->getStock() ?></p>
          <p class="card-text text-success fw-bold">$<?= $producto->getPrecio(); ?></p>
          <?php  if ($rolUsuario == 1): ?>
          <button class="agregar-carrito btn btn-primary mt-auto" data-id="<?=$producto->getIdProducto();?>" data-nombre="<?=$producto->getNombre();?>">
          Agregar al carrito</button>
           <?php  else: ?>
           <div class="mt-auto text-center w-100">
            <span class="fw-bold">Inicia sesión</span>
          </div>
        </div>
         <?php  endif; ?>
      </div>
    </div>
<?php
  }else{?>
    <div class="col">
      <div class="card h-100">
        <div style="opacity: 0.5;" class="card-body d-flex flex-column">
          <img height="350" width="50" src="<?= $producto->getImagen(); ?>" class="card-img-top" alt="Producto no disponible">
        <h5 class="card-title">No disponible</h5>
    </div>
    <div class="card-footer">
          <h5 style="text-align: center;"><?= $producto->getNombre() ?></h5>
      </div>
    </div>
  <?php }
}?>
  </div>
</div>
   
<script src="./assets/js/carrito.js"></script>
<link rel="stylesheet" href="./assets/css/carrito.css">
<?php
include_once 'structure/footer.php';
?>