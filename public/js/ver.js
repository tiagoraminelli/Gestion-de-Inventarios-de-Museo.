$(document).ready(function () {
  $(".btn-ver").click(function () {
    let idPieza = $(this).data("id");
    console.log("ID de Pieza:", idPieza);

    // Mostrar modal
    let modal = new bootstrap.Modal(document.getElementById("modalVerPieza"));
    modal.show();

    // Mostrar loader mientras carga
    $("#contenidoModalPieza").html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `);

    // Petición AJAX
    $.ajax({
      url: "../Controller/PiezaController.php",
      method: "GET",
      data: { accion: "ver", idPieza: idPieza },
      dataType: "json",
      success: function (pieza) {
        if (pieza) {
          let rutaImagen = "../assets/upload/placeholder.webp";
          let html = `
    <div style="display:flex; flex-wrap: wrap; gap:2rem;">
        <div style="flex: 0 0 250px; text-align:center;">
            <img src="${rutaImagen}" alt="Imagen de pieza">
        </div>
        <div style="flex: 1 1 auto;">
            <div class="detalle-item"><strong>ID:</strong> ${
              pieza.idPieza
            }</div>
            <div class="detalle-item"><strong>Inventario:</strong> ${
              pieza.num_inventario
            }</div>
            <div class="detalle-item"><strong>Especie:</strong> ${
              pieza.especie
            }</div>
            <div class="detalle-item"><strong>Estado:</strong> ${
              pieza.estado_conservacion
            }</div>
            <div class="detalle-item"><strong>Fecha Ingreso:</strong> ${
              pieza.fecha_ingreso
            }</div>
            <div class="detalle-item"><strong>Cantidad:</strong> ${
              pieza.cantidad_de_piezas
            }</div>
            <div class="detalle-item"><strong>Clasificación:</strong> ${
              pieza.clasificacion
            }</div>
            <div class="detalle-item"><strong>Observación:</strong> ${
              pieza.observacion
            }</div>
            <div class="detalle-item"><strong>ID Donante:</strong> ${
              pieza.Donante_idDonante
            }</div>
            <div class="detalle-item"><strong>Creado:</strong> ${
              pieza.created_at ?? ""
            }</div>
            <div class="detalle-item"><strong>Actualizado:</strong> ${
              pieza.updated_at ?? ""
            }</div>
        </div>
    </div>
`;

          $("#contenidoModalPieza").html(html);
        } else {
          $("#contenidoModalPieza").html(
            '<div class="alert alert-danger">No se encontró la pieza.</div>'
          );
        }
      },
      error: function () {
        $("#contenidoModalPieza").html(
          '<div class="alert alert-danger">Error al cargar los datos.</div>'
        );
      },
    });
  });
});
