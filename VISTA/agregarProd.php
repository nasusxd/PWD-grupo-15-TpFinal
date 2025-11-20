<?php
$titulo = "Nuevo Producto";
include_once "../configuracion.php";
$sesion = new Session();
$sesion->validarLogin(true);
include_once './structure/header.php';
?>



<div class="container mt-5">
    <h1 class="text-center">Cargar Nuevo Producto</h1>

    <div class="d-flex justify-content-center mt-4">

        <form id="formProducto" class="w-50" enctype="multipart/form-data">
        <div class="mb-0">
            <label for="pronombre">Nombre del Producto:</label>
            <input type="text" id="pronombre"class="form-control" name="pronombre" required><br><br>
        </div>

        <div class="mb-0">
            <label for="prodetalle">Detalle del Producto:</label>
            <textarea id="prodetalle"class="form-control" name="prodetalle" required></textarea><br><br>
        </div>

        <div class="mb-0">
            <label for="procantstock">Cantidad en Stock:</label>
            <input type="number" id="procantstock"class="form-control" name="procantstock" min="0" required><br><br>
        </div>

        <div class="mb-0">
            <label for="precio">Precio:</label>
            <input type="number" id="precio"class="form-control" name="precio" step="0.01" required><br><br>
        </div>

        <div class="mb-0">
            <label for="proimagen">Imagen del Producto:</label>
            <input type="file" id="proimagen" class="form-control" name="proimagen" accept="image/*" required><br><br>
        </div>

        <div class="mb-0">
            <label for="descuento">Descuento:</label>
            <input type="number" id="descuento"class="form-control" name="descuento" min="0" required><br><br>
        </div>

        <button type="submit" id="btn-cargar" value="Cargar" class="btn btn-primary">Cargar Producto</button>
        <div id="mensaje" style="margin-top: 20px;"></div>
            <hr class="mt-4">
             <a href="./index.php" class="btn btn-danger">Volver</a>
        </form>

  </div>
</div>
<script>
  $(document).ready(function() {
    $("#btn-cargar").click(function(e) {
      e.preventDefault();
      // Limpiar mensajes previos
      $("#mensaje").empty();

      let formData = new FormData()
      formData.append("pronombre", $("#pronombre").val());
      formData.append("prodetalle", $("#prodetalle").val());
      formData.append("procantstock", $("#procantstock").val());
      formData.append("precio", $("#precio").val());
      formData.append("descuento", $("#descuento").val());
        let archivo = $("#proimagen")[0].files[0];
        if (archivo) {
            formData.append("proimagen", archivo);
        } 


      
      $.ajax({
        type: 'POST',
        url: 'action/actionAgregarProd.php',
        data: formData,
        contentType: false,
        processData: false, 
        dataType: 'json',
        success: function(respuesta) {
          if (respuesta.success) {
            $("#mensaje").html(
              `<div class="alert alert-success">${respuesta.message}</div>`
            );
          } else {
            $("#mensaje").html(
              `<div class="alert alert-danger">${respuesta.message}</div>`
            );
          }
        },
        error: function(error) {
          $("#mensaje").html(
            '<div class="alert alert-danger">Error en la conexión al servidor.</div>'
          );
          console.log(error);
        },
      });
    });
  });
</script>


<?php include_once "./structure/footer.php"; ?>