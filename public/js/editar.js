$(document).ready(function () {
  $(".btn-editar").click(function () {
    let idPieza = $(this).data("id");
    let modalEl = document.getElementById("modalEditarPieza");
    let modal = new bootstrap.Modal(modalEl);
    modal.show();

    $("#contenidoModalEditarPieza").html(`
      <div class="text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>
    `);

    // Obtener datos de la pieza
    $.ajax({
      url: '../Controller/PiezaController.php',
      method: 'GET',
      data: { accion: 'ver', idPieza: idPieza },
      dataType: 'json',
      success: function (pieza) {
        if (!pieza) return;

        let html = `
          <form id="formEditarPieza">
            <div class="mb-3">
              <label>Inventario</label>
              <input type="text" name="num_inventario" class="form-control" value="${pieza.num_inventario}">
            </div>
            <div class="mb-3">
              <label>Especie</label>
              <input type="text" name="especie" class="form-control" value="${pieza.especie}">
            </div>
            <div class="mb-3">
              <label>Estado</label>
              <input type="text" name="estado_conservacion" class="form-control" value="${pieza.estado_conservacion}">
            </div>
            <div class="mb-3">
              <label>Fecha Ingreso</label>
              <input type="date" name="fecha_ingreso" class="form-control" value="${pieza.fecha_ingreso}">
            </div>
            <div class="mb-3">
              <label>Cantidad de piezas</label>
              <input type="text" name="cantidad_de_piezas" class="form-control" value="${pieza.cantidad_de_piezas}">
            </div>
            <div class="mb-3">
              <label>Clasificación</label>
              <select name="clasificacion" class="form-select">
                <option ${pieza.clasificacion === 'Biologia' ? 'selected' : ''}>Biologia</option>
                <option ${pieza.clasificacion === 'Zoologia' ? 'selected' : ''}>Zoologia</option>
                <option ${pieza.clasificacion === 'Botanica' ? 'selected' : ''}>Botanica</option>
                <option ${pieza.clasificacion === 'Geologia' ? 'selected' : ''}>Geologia</option>
                <option ${pieza.clasificacion === 'Paleontologia' ? 'selected' : ''}>Paleontologia</option>
                <option ${pieza.clasificacion === 'Arqueologia' ? 'selected' : ''}>Arqueologia</option>
              </select>
            </div>
            <div class="mb-3">
              <label>Observación</label>
              <textarea name="observacion" class="form-control">${pieza.observacion}</textarea>
            </div>
            <div id="erroresEditar" class="text-danger mb-2"></div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </form>
        `;

        $("#contenidoModalEditarPieza").html(html);

        // Evento submit
        $("#formEditarPieza").off('submit').on('submit', function (e) {
          e.preventDefault();
          $("#erroresEditar").html(''); // Limpiar errores

          let formData = {};
          $(this).serializeArray().forEach(input => formData[input.name] = input.value);

          $.ajax({
            url: '../Controller/PiezaController.php',
            method: 'POST',
            data: { accion: 'editar', idPieza: idPieza, ...formData },
            dataType: 'json',
            success: function (res) {
              if (res.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'La pieza se ha actualizado correctamente.',
                  timer: 2000,
                  showConfirmButton: false
                });

                // Cerrar modal automáticamente después de 2s
                setTimeout(() => modal.hide(), 2000);

                // Actualizar la página o recargar solo el grid si querés
                // location.reload();
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  html: res.errors ? res.errors.join('<br>') : 'Hubo un error al actualizar la pieza'
                });
              }
            },
            error: function () {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo conectar con el servidor'
              });
            }
          });
        });
      }
    });
  });
});
