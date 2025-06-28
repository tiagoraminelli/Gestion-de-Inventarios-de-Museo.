$.ajax({
  url: '../../Controller/PiezaController.php',
  type: 'POST',
  data: {
    action: 'destroyMultiple',
    ids: selectedIds
  },
  dataType: 'text', // 👈 Para ver lo crudo
  success: function(responseText) {
    console.log("Respuesta cruda:", responseText);
    try {
      const response = JSON.parse(responseText);
      if (response.success) {
        Swal.fire('Eliminado!', 'Las piezas seleccionadas han sido eliminadas.', 'success');
        location.reload(); // o actualizar DataTable si es dinámico
      } else {
        Swal.fire('Error', response.message || 'Hubo un problema al eliminar las piezas.', 'error');
      }
    } catch (e) {
      Swal.fire('Error', 'Respuesta del servidor no es JSON válido.', 'error');
      console.error("Error de parseo JSON:", e, responseText);
    }
  },
  error: function(xhr, status, error) {
    console.error('Error en AJAX:', status, error);
    Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
  }
});
