<?php include_once 'structure/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="width: 450px;">
        <div class="card-body">
            <h3 class="text-center mb-4" id="tituloFormulario">Registrarse</h3>

            <form id="registroForm" action="action/actionRegistro.php" method="POST">


                <div class="mb-3">
                    <input type="text" class="form-control" name="usnombre" placeholder="Ingrese su nombre" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="usmail" placeholder="Ingrese su email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="uspass" placeholder="Ingrese una contraseña" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="uspassConfirm" placeholder="Confirme su contraseña" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
            </form>
        </div>
    </div>
</div>

<script src="js/registro.js"></script>

<?php include_once 'structure/footer.php'; ?>
