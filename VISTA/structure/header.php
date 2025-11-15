<?php
include_once(__DIR__ . '../../../configuracion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <script src="../assets/LibraryjQuery/jquery-3.7.1.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/bootstrap-5.1.3-dist/css/bootstrap.css">
  <script src="<?= BASE_URL ?>assets/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
  <title>TPFINAL - Programación Web Dinámica</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>
<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        
      <!-- LOGO -->
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>menu.php"><img src="../../juegunco.ico" height="50" width="50"> Juegunco</a>

    <!-- BOTÓN HAMBURGUESA -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuTienda" aria-controls="menuTienda" aria-expanded="false" aria-label="Alternar menú">
      <span class="navbar-toggler-icon"></span>
    </button>


    <!-- CONTENIDO DEL MENÚ -->
    <div class="collapse navbar-collapse" id="menuTienda">

      <!-- LINKS PRINCIPALES -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>productos.php">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>ofertas.php">Ofertas</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contacto.php">Contacto</a></li>
      </ul>

      <!-- BUSCADOR -->
      <form class="d-flex me-3" action="<?= BASE_URL ?>buscar.php" method="get">
        <input class="form-control me-2" type="search" name="q" placeholder="Buscar productos..." aria-label="Buscar">
        <button class="btn btn-outline-primary" type="submit">Buscar</button>
      </form>
      <br>

      <!-- ICONOS (USUARIO / CARRITO) -->
      <div class="d-flex align-items-center">
        <!-- USUARIO -->
        <a href="<?= BASE_URL ?>login.php" class="btn btn-link text-dark fs-5 me-3 p-0" title="Mi cuenta">
          <i class="bi bi-person-circle"></i>
        </a>

        <!-- ICONO MENÚ -->
         <a href="<?= BASE_URL ?>menu.php" class="btn btn-link text-dark fs-5 me-3 p-0" title="Menú">
          <i class="bi bi-house-fill"></i>
        </a>

        <!-- CARRITO -->
        <a href="<?= BASE_URL ?>carrito.php" class="btn btn-link text-dark fs-5 p-0 position-relative" title="Carrito">
          <i class="bi bi-cart3"></i>
          <!-- contador del carrito -->
          <span id="contador-carrito" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            0
          </span>
        </a>
      </div>
      </div>
    </div>
  </nav>
</header>

<script src="<?= BASE_URL ?>assets/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
