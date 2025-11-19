<?php
include_once(__DIR__ . '../../../configuracion.php');
$objSession = new Session(); // Instancia de la sesión
$rolUsuario = $objSession->getRol(); // Esto te dará el rol del usuario
$idUsuario = $objSession->getUsuario();
$totalCarrito = $objSession->totalProductosCarrito();

$objAbmMenu = new ABMMenu();
$opcionProductos = $objAbmMenu->buscar(['idmenu' => 2]);
$opcionContacto = $objAbmMenu->buscar(['idmenu' => 5]);
$estadoMenuProductos = $opcionProductos[0]->getDeshabilitado();
$estadoMenuContacto = $opcionContacto[0]->getDeshabilitado();

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
<body class="d-flex flex-column min-vh-100">
<header class="container mt-3 mb-4">
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-3 px-3 py-2 border">
    <div class="container-fluid">
        
      <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>menu.php">
        <img src="../img/pelunco.svg" alt="Logo" height="45" class="d-inline-block align-text-top">
      </a>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuTienda" aria-controls="menuTienda" aria-expanded="false" aria-label="Alternar menú">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuTienda">

        <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
          <!-- Menú principal con submenú -->
    <?php if ($estadoMenuProductos == 0) { ?>
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle px-3" href="#" id="productosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      Productos
    </a>
    <ul class="dropdown-menu" aria-labelledby="productosDropdown">
      <li><a class="dropdown-item" href="<?= BASE_URL ?>productos.php">Todos los productos</a></li>
      <li><a class="dropdown-item" href="<?= BASE_URL ?>ofertas.php">Ofertas</a></li>
    </ul>
  </li>
  <?php } ?>
  <?php if ($estadoMenuContacto == 0) { ?>
  <!-- Ítems normales fuera del dropdown -->
  <li class="nav-item">
    <a class="nav-link px-3" href="<?= BASE_URL ?>contacto.php">Contacto</a>
  </li>
  <?php } ?>
        </ul>

        <?php if ($rolUsuario == [] || $rolUsuario[0] != 2): ?>
        <form class="d-flex me-3" action="<?= BASE_URL ?>buscar.php" method="get">
          <div class="input-group">
            <input class="form-control border-end-0 rounded-start" type="search" name="q" placeholder="Buscar..." aria-label="Buscar">
            <button class="btn btn-outline-secondary border-start-0 rounded-end" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>
        <?php endif; ?>

        <div class="d-flex align-items-center gap-3">
          
          <?php if ($rolUsuario !== [] ): ?>
          <div class="dropdown">
              <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
                <span class="d-none d-lg-inline">Mi Cuenta</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="<?= BASE_URL ?>modificarCliente.php">Modificar mi Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout.php">Cerrar sesión</a></li>
              </ul>
            </div>
          
          <?php else: ?>
          <a href="<?= BASE_URL ?>login.php" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
              Ingresar
            </a>
          <?php endif; ?>

          <a href="<?= BASE_URL ?>menu.php" class="text-secondary fs-5" title="Inicio">
            <i class="bi bi-house-door-fill"></i>
          </a>

          <?php if ($rolUsuario !== [] && $rolUsuario[0] !== 2): ?>
          <a href="#" class="modalCarrito text-dark fs-5 position-relative" title="Carrito" 
          data-bs-toggle="modal" data-bs-target="#modalCarrito">
          <i class="bi bi-cart3"></i>
    <span id="contador-carrito" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
        <?= $totalCarrito ?>
    </span>
</a>

          <?php endif; ?>

        </div>
      </div>
    </div>
  </nav>
</header>

        <div class="modal fade" id="modalCarrito" tabindex="-1">
            <div class="modal-dialog modal-md">
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
                <button class="btn btn-success w-100" id="btn-finalizar">Finalizar compra</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>

<script src="<?= BASE_URL ?>assets/js/carrito.js"></script>
<script src="<?= BASE_URL ?>assets/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

