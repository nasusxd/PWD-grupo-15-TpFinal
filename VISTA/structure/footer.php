<?php
$objAbmMenu = new ABMMenu();
$opcionTodosProductos = $objAbmMenu->buscar(['idmenu' => 3]);
$opcionOfertas = $objAbmMenu->buscar(['idmenu' => 4]);
$estadoMenuTodosProductos = $opcionTodosProductos[0]->getDeshabilitado();
$estadoMenuOfertas = $opcionOfertas[0]->getDeshabilitado();

?>
<footer class="bg-dark text-white pt-5 pb-4 mt-5 ">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">

            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Grupo 15</h5>
                <p>
                    Trabajo Práctico Final de <strong>Programación Web Dinámica</strong>.
                    Desarrollo de una tienda online completa con carrito de compras y gestión de usuarios.
                </p>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Integrantes</h5>
                <p>
                    <i class="bi bi-person-fill me-2"></i> Sofia Bascur
                </p>
                <p>
                    <i class="bi bi-person-fill me-2"></i> Nahuel Gonzalez
                </p>
                <p>
                    <i class="bi bi-person-fill me-2"></i> Juan Sastre
                </p>
            </div>

           <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
    <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Enlaces</h5>

    <ul class="list-unstyled">
        <?php if ($estadoMenuTodosProductos == 0) { ?>
        <li class="mb-2">
            <a href="<?= BASE_URL ?>productos.php" class="text-white text-decoration-none">
                Todos los productos
            </a>
        </li>
        <?php } ?>
        <?php if ($estadoMenuOfertas == 0) { ?>
        <li class="mb-2">
            <a href="<?= BASE_URL ?>ofertas.php" class="text-white text-decoration-none">
                Ofertas
            </a>
        </li>
        <?php } ?>
        <li class="mb-2">
            <a href="https://www.uncoma.edu.ar/" target="_blank" class="text-white text-decoration-none">
                UNCo
            </a>
        </li>
    </ul>
</div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Contacto</h5>
                <p>
                    <i class="bi bi-house-fill me-3"></i> Neuquén, Argentina
                </p>
                <p>
                    <i class="bi bi-envelope-fill me-3"></i> pwd@grupo15.com
                </p>
                <p>
                    <i class="bi bi-github me-3"></i> <a href="https://github.com/nasusxd/PWD-grupo-15-TpFinal" class="text-white text-decoration-none">Repositorio GitHub</a>
                </p>
            </div>
        </div>

        <hr class="mb-4">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p> © 2025 Todos los derechos reservados por:
                    <a href="#" style="text-decoration: none;">
                        <strong class="text-primary">Grupo 15 - PWD</strong>
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="./assets/js/validacion.js"></script>

</body>
</html>