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
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

       <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>index.php"><img src="../img/pelunco.svg" height="40" width="auto"></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuAdmin">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuAdmin">

        <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>

        <div class="d-flex align-items-center">

          <?php if ($login): ?>
            <span class="text-dark me-3">
              <i class="bi bi-person-gear"></i> Admin: <?= $usuario[0]->getNombre() ?>
            </span>

            <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline-danger me-2">
              Cerrar sesión
            </a>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </nav>
</header>

</body>
</html>
