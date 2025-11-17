<?php
include_once './structure/header.php';
$sesion = new Session();

$idUsuario = $sesion->getUsuario();
$objAbmUsuarioRol = new ABMUsuarioRol();
$objUsuarioRol = $objAbmUsuarioRol->buscar(['idusuario' => $idUsuario]);

$objProducto = new ABMProducto();
$listaProductos = $objProducto->buscar(null);
?>
<br>
<div id="miCarrusel" class="carousel slide" data-bs-ride="carousel">
  
  <!-- Indicadores -->
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
foreach ($listaProductos as $producto):
    $disponible = $producto->getStock() > 0;
?>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="../uploads/<?= $producto->getImagen(); ?>" 
             class="card-img-top img-fluid" 
             style="height: 250px; object-fit: cover;" 
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
                <span class="fw-bold">Inicia sesión</span>
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
<?php endforeach; ?>
  </div>
</div>

<script src="./assets/js/carrito.js"></script>
<link rel="stylesheet" href="./assets/css/carrito.css">

<?php
include_once 'structure/footer.php';
?>
