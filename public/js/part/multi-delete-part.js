$(document).ready(function() {
  // Manejo de selección de todos los checkboxes
  $('#checkAll').on('click', function() {
    const isChecked = $(this).prop('checked');
    $('.check-item').prop('checked', isChecked);
  });

  // Eliminar las piezas seleccionadas
  $('#btnEliminarSeleccionados').on('click', function() {
    const selectedIds = [];
    
    // Recopilar los ID de las piezas seleccionadas
    $('.check-item:checked').each(function() {
      selectedIds.push($(this).data('id'));  // Asegúrate de que el ID esté en data('id')
    });

    // Comprobar si hay elementos seleccionados
    if (selectedIds.length > 0) {
      // Confirmación antes de eliminar
      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción eliminará las piezas seleccionadas!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Enviar la solicitud AJAX para eliminar las piezas seleccionadas
          console.log(selectedIds);  // Mostrar los IDs en la consola
          $.ajax({
            url: '../../Controller/PiezaController.php',  // Ruta correcta para el controlador
            type: 'POST',
            data: {
              action: 'destroyMultiple',  // Acción que el controlador PHP reconocerá
              ids: selectedIds           // Array con los IDs seleccionados
            },
            success: function(response) {
              // Comprobar la respuesta del servidor
              console.log(response); // Mostrar respuesta para depuración
              if (response.success) {
                Swal.fire('Eliminado!', 'Las piezas seleccionadas han sido eliminadas.', 'success');
                $('#tablaPieza').DataTable().ajax.reload(); // Recargar la tabla para reflejar los cambios
              } else {
                Swal.fire('Error', response.message || 'Hubo un problema al eliminar las piezas.', 'error');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error en AJAX:', status, error);
              Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
              
            }
          });
        }
      });
    } else {
      Swal.fire('Advertencia', 'No se seleccionaron piezas para eliminar.', 'warning');
    }
  });
});
