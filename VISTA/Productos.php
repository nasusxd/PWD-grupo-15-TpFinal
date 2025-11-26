<?php
include_once(__DIR__ . '/../configuracion.php');

$sesion = new Session();
$sesion->validarLogin(false); // Permite ver productos sin logueo

include_once './structure/header.php';

// ----- OBTENER MENÚ -----
$objAbmMenu = new ABMMenu();
$menuTodosProductos = $objAbmMenu->buscar(['idmenu' => 3]);
$estadoMenu = $menuTodosProductos[0]->getDeshabilitado();

// ----- OBTENER ROL USUARIO (si existe sesión) -----
$idUsuario = $sesion->getUsuario();
$objAbmUsuarioRol = new ABMUsuarioRol();
$rolUsuario = null;

if ($idUsuario !== null) {
    $roles = $objAbmUsuarioRol->buscar(['idusuario' => $idUsuario]);
    if (!empty($roles)) {
        $rolUsuario = $roles[0]->getIdRol();
    }
}

// ----- OBTENER PRODUCTOS -----
$objProducto = new ABMProducto();
$listaProductos = $objProducto->buscar(null);

// SI EL MENÚ ESTÁ DESHABILITADO
if ($estadoMenu == 1) {
    echo "<div class='alert alert-warning text-center' role='alert'>
            Los productos están deshabilitados.
          </div>";
} else {
?>
<br><hr>

<div class="container">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

<?php foreach ($listaProductos as $producto): 

    $estaDeshabilitado = $producto->getProdeshabilitado() == 1;
    $sinStock = $producto->getStock() <= 0;

    // Disponible solo si tiene stock Y no está deshabilitado
    $disponible = !$estaDeshabilitado && !$sinStock;

    $esOferta = $producto->getDescuento() > 0;
    $precioFinal = $esOferta 
        ? $producto->getPrecio() * (1 - $producto->getDescuento() / 100) 
        : $producto->getPrecio();

    // Texto del estado
    if ($estaDeshabilitado) {
        $estadoTexto = "Deshabilitado";
    } elseif ($sinStock) {
        $estadoTexto = "Sin stock";
    } else {
        $estadoTexto = "Disponible";
    }

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

          <!-- Estado del producto -->
          <?php if (!$disponible): ?>
            <div class="text-center text-muted" style="opacity: .6;">
              <h5 class="card-title"><?= $estadoTexto ?></h5>
              <p class="mb-0"><?= $producto->getNombre() ?></p>
            </div>
          <?php else: ?>
            
            <h5 class="card-title mb-2"><?= $producto->getNombre() ?></h5>
            <p class="mb-1">Cantidad disponible: <?= $producto->getStock() ?></p>

            <?php if (!$esOferta): ?>
                <p class="card-text text-success fw-bold mb-3">
                    $<?= $producto->getPrecio(); ?>
                </p>
            <?php else: ?>
                <p class="card-text text-success fw-bold mb-0">
                    Antes: $<?= $producto->getPrecio(); ?>
                </p>
                <p class="text-danger fw-bold mb-1">
                    Oferta: $<?= number_format($precioFinal, 2); ?>
                </p>
            <?php endif; ?>

            <?php if ($rolUsuario === 1): ?>
              <button class="agregar-carrito btn btn-primary mt-auto"
                      data-id="<?= $producto->getIdProducto(); ?>"
                      data-nombre="<?= $producto->getNombre(); ?>"
                      data-descuento="<?= $producto->getDescuento(); ?>">
                Agregar al carrito
              </button>
            <?php else: ?>
              <div class="mt-auto text-center w-100">
                <span class="fw-bold">Inicia sesión para comprar</span>
              </div>
            <?php endif; ?>

          <?php endif; ?>

        </div>
      </div>
    </div>

<?php endforeach; ?>


  </div>
</div>

<?php } ?> <!-- cierre if menu habilitado -->

<script src="./assets/js/carrito.js"></script>

<link rel="stylesheet" href="./assets/css/ofertas.css">

<style>
.img-blur {
    filter: blur(1px);
    opacity: 0.4;
}
</style>

<?php include_once 'structure/footer.php'; ?>
