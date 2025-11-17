<?php
include_once(__DIR__ . '../../../configuracion.php');
$sesion = new Session();
$login = $sesion->validar();
$idUsuario = $login ? $sesion->getUsuario() : null;
$objUsuario = new ABMUsuario();
$usuario = $objUsuario->buscar(['idusuario' => $idUsuario]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/bootstrap-5.1.3-dist/css/bootstrap.css">
  <script src="<?= BASE_URL ?>assets/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
  <header class="container mt-3 mb-4">
    <nav class="navbar navbar-expand-lg navbar-light shadow rounded-3 px-3 py-2 border"
      style="background: linear-gradient(135deg, #ffffff 0%, #eef2f3 100%); border-top: 4px solid #0d6efd !important;">

      <div class="container-fluid">

        <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>index.php">
          <img src="../img/pelunco.svg" height="45" width="auto" alt="Logo">
          <div class="ms-3 ps-3 border-start border-2 border-secondary d-flex flex-column justify-content-center" style="line-height: 1;">
            <span class="text-uppercase text-primary fw-bold" style="font-size: 12px; letter-spacing: 1px;">Panel</span>
            <span class="text-dark fw-bold" style="font-size: 14px;">Administración</span>
          </div>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuAdmin">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuAdmin">

          <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>

          <div class="d-flex align-items-center gap-3">

            <?php if ($login): ?>

              <div class="d-flex align-items-center bg-white px-3 py-1 rounded-pill border shadow-sm">
                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2"
                  style="width: 32px; height: 32px;">
                  <i class="bi bi-person-gear-fill"></i>
                </div>

                <div class="d-flex flex-column me-2">
                  <small class="text-muted text-uppercase" style="font-size: 9px; line-height: 10px;">Bienvenido</small>
                  <span class="fw-bold text-dark" style="font-size: 14px;"><?= $usuario[0]->getNombre() ?></span>
                </div>
              </div>

              <a href="<?= BASE_URL ?>logout.php" class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                style="width: 38px; height: 38px;" title="Cerrar sesión">
                <i class="bi bi-box-arrow-right fs-6"></i>
              </a>

            <?php endif; ?>

          </div>
        </div>
      </div>
    </nav>
  </header>

</html>