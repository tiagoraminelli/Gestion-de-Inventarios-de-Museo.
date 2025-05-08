function eliminarPieza(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '../../Controller/PiezaController.php',
                data: {
                    action: 'destroy',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            '¡Eliminado!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', status, error);
                    Swal.fire(
                        'Error',
                        'Ocurrió un error al intentar eliminar la pieza.',
                        'error'
                    );
                }
            });
        }
    });
}
