<?php include_once 'structure/headerGlobal.php'; ?>

<style>
.text-danger {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    
    <div class="card shadow-sm" style="width: 450px;">
        <div class="card-body">
            <h3 class="text-center mb-4" id="tituloFormulario">Registrarse</h3>

            <form id="registroForm" action="action/actionRegistro.php" method="POST">

                <div class="mb-3">
                    <input type="text" id="usnombre" class="form-control" name="usnombre" placeholder="Ingrese su nombre" required>
                </div>
                <div class="mb-3">
                    <input type="email" id="usmail" class="form-control" name="usmail" placeholder="Ingrese su email" required>
                </div>
                <div class="mb-3">
                    <input type="password" id="uspass" class="form-control" name="uspass" placeholder="Ingrese una contraseña" required>
                </div>

                <div id="mensaje" class="mb-3"></div>

                <button type="button" id="registerButton" class="btn btn-primary w-100">Registrarse</button>
            </form>
        </div>
    </div>
</div>

<script src="./assets/js/register.js"></script>

<?php include_once 'structure/footer.php'; ?>
