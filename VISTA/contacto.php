<?php 
include_once(__DIR__ . '/../configuracion.php');
$sesion = new Session();
$sesion->validarLogin(false);
include_once 'structure/header.php';

?>
<body>
<div class="container mt-5">
    <h1>Contacto</h1>
    <p>Por favor, complete el formulario a continuación para ponerse en contacto con nosotros. Estaremos encantados de atender su consulta.</p>
<br>
    <!-- Formulario de contacto -->
    <div class="row">
        <div class="col-md-8 offset-md-2">
        <div id="datosContacto">
            <form action="contacto.php" method="POST" name="contactoForm" id="contactoForm">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>

                <div class="mb-3">
                    <label for="asunto" class="form-label">Asunto</label>
                    <input type="text" class="form-control" id="asunto" name="asunto" required>
                </div>

                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                </div>

                <button type="button" id="contactoButton" class="btn btn-primary">Enviar Mensaje</button>
            </form>
            </div>
        </div>
    </div>
    <script src="./assets/js/contacto.js"></script>

<br><br><br>

</div>
<br>
<?php 
include_once 'structure/footer.php';
?>
</html>