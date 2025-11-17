<?php
include_once(__DIR__ . '../../../configuracion.php');
$objSession = new Session(); // Instancia de la sesión
$rolUsuario = $objSession->getRol(); // Esto te dará el rol del usuario
$idUsuario = $objSession->getUsuario();

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
  <link rel="stylesheet" href="assets/css/menu.css">



</head>
<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light py-1">
    <div class="container-fluid">
        
      <!-- LOGO -->
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>menu.php"><img src="../img/pelunco.svg" height="40" width="auto"></a>

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

      <?php if ($rolUsuario == [] || $rolUsuario[0] != 2): ?>
      <!-- BUSCADOR -->
      <form class="d-flex me-3" action="<?= BASE_URL ?>buscar.php" method="get">
        <input class="form-control me-2" type="search" name="q" placeholder="Buscar productos..." aria-label="Buscar">
        <button class="btn btn-outline-primary" type="submit">Buscar</button>
      </form>
      <br>
      <?php endif; ?>

      <!-- ICONOS (USUARIO) -->
      <div class="d-flex align-items-center">
        <?php if ($rolUsuario !== [] ): ?>
        <!-- Usuario cliente -->
        <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline-danger me-2">
        Cerrar sesión
        </a>

        <a href="<?= BASE_URL ?>modificarCliente.php" class="btn btn-link text-dark fs-5 me-3 p-0" title="Mi cuenta">
            <i class="bi bi-person-circle"></i>
        </a>
        
        <?php else: ?>
        <a href="<?= BASE_URL ?>login.php" class="btn btn-primary me-2">
        Iniciar sesión
        </a>
        <?php endif; ?>

        <!-- ICONO MENÚ -->
         <a href="<?= BASE_URL ?>menu.php" class="btn btn-link text-dark fs-5 me-3 p-0" title="Menú">
          <i class="bi bi-house-fill"></i>
        </a>

        <?php if ($rolUsuario !== [] && $rolUsuario[0] !== 2): ?>
        <!-- CARRITO -->
        <a href="#" class="btn btn-link text-dark fs-5 p-0 position-relative" title="Carrito" data-bs-toggle="modal" data-bs-target="#modalCarrito">
          <i class="bi bi-cart3"></i>
          <!-- contador del carrito -->
          <span id="contador-carrito" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            0
          </span>
        </a>

        <div class="modal fade" id="modalCarrito" tabindex="-1">
            <div class="modal-dialog modal-sm">
              <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title">Tu Carrito</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <ul class="list-group mb-3" id="lista-carrito"></ul>
                <div class="d-flex justify-content-between">
                  <strong>Total:</strong> <span id="total-carrito">$0</span>
                </div>
              </div>
              
              <div class="modal-footer">
                <button id="btn-finalizar"  class="alert alert-dismissible fade show mt-3" role="alert">
                  Finalizar compra</button>
              </div>
            </div>
          </div>
        </div>
        <?php elseif ($rolUsuario == 2):
          header('Location: ./structure/headerAdmin.php'); ?>
      </div>
      </div>
      <?php endif; ?>

    </div>
  </nav>
</header>

<script src="<?= BASE_URL ?>assets/js/compra.js"></script>
<script src="<?= BASE_URL ?>assets/js/carrito.js"></script>
<script src="<?= BASE_URL ?>assets/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

