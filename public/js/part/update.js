$('#tablaPieza').on('click', '.btn-editar', function() {
  const id = $(this).data('id');

  // Petición para obtener los datos de la pieza
  $.ajax({
    url: '../../Controller/PiezaController.php',
    method: 'GET',
    data: { accion: 'obtener', idPieza: id },
    dataType: 'json',
    success: function(pieza) {
      // Rellenar el formulario con los datos recibidos
      $('#edit_idPieza').val(pieza.idPieza);
      $('#edit_num_inventario').val(pieza.num_inventario);
      $('#edit_especie').val(pieza.especie);
      // Completa con los demás campos

      // Abrir el modal
      const modal = new bootstrap.Modal(document.getElementById('modalEditarPieza'));
      modal.show();
    },
    error: function() {
      Swal.fire('Error', 'No se pudo cargar la pieza para editar.', 'error');
    }
  });
});
