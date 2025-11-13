<?php
include_once './structure/header.php';
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
      <img src="./assets/img/ims/diseno-fondo-acuarela-arco-iris_23-2149574508.avif" class="d-block w-100" alt="Imagen 1">
    </div>
    <div class="carousel-item">
      <img src="./assets/img/ims/fondo-abstracto-acuarela-colorida_23-2149108517.avif" class="d-block w-100" alt="Imagen 2">
    </div>
    <div class="carousel-item">
      <img src="./assets/img/ims/fondo-textura-acuarela-color-arco-iris_1048-16710.avif" class="d-block w-100" alt="Imagen 3">
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
    
    <!-- Producto 1 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+1" class="card-img-top" alt="Producto 1">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 1</h5>
          <p class="card-text text-success fw-bold">$1000</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>

    <!-- Producto 2 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+2" class="card-img-top" alt="Producto 2">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 2</h5>
          <p class="card-text text-success fw-bold">$1500</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>

    <!-- Producto 3 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+3" class="card-img-top" alt="Producto 3">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 3</h5>
          <p class="card-text text-success fw-bold">$2000</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>

    <!-- Producto 4 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+4" class="card-img-top" alt="Producto 4">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 4</h5>
          <p class="card-text text-success fw-bold">$1200</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
    <!-- PRODUCTOS 2 -->
<div class="container">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    
    <!-- Producto 1 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+1" class="card-img-top" alt="Producto 1">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 5</h5>
          <p class="card-text text-success fw-bold">$1000</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>

    <!-- Producto 2 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+2" class="card-img-top" alt="Producto 2">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 6</h5>
          <p class="card-text text-success fw-bold">$1500</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>

    <!-- Producto 3 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+3" class="card-img-top" alt="Producto 3">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 7</h5>
          <p class="card-text text-success fw-bold">$2000</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>

    <!-- Producto 4 -->
    <div class="col">
      <div class="card h-100">
        <img src="https://via.placeholder.com/300x300?text=Producto+4" class="card-img-top" alt="Producto 4">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Producto 8</h5>
          <p class="card-text text-success fw-bold">$1200</p>
          <button class="btn btn-primary mt-auto">Agregar al carrito</button>
        </div>
      </div>
    </div>

  </div>
</div>
<style>
 #miCarrusel .carousel-item img {
  width: 100%;
  height: auto;         /* altura automática según proporción */
  max-height: 400px;    /* opcional para limitar tamaño */
  object-fit: contain;  /* mantiene proporción */
  margin: 0 auto;       /* centra horizontalmente */
  background-color: #ffffffff; /* opcional, fondo negro donde sobra */
}


</style>
<?php
include_once 'structure/footer.php';
?>