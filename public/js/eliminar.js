$(document).ready(function () {
    $(".btn-eliminar").click(function () {
        let idPieza = $(this).data("id");

        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás deshacer esta acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../Controller/PiezaController.php',
                    method: 'POST',
                    data: {
                        accion: 'eliminar',
                        id: idPieza
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire(
                                'Eliminado!',
                                'La pieza ha sido eliminada.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'No se pudo eliminar la pieza.',
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            'Hubo un problema al comunicarse con el servidor.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
