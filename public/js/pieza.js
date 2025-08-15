$(document).ready(function () {
    const tabla = $('#tablaPieza').DataTable({
        data: window.datosPieza,
        columns: [
            { data: 'idPieza' },
            { data: 'num_inventario' },
            { data: 'especie' },
            { data: 'estado_conservacion' },
            { data: 'fecha_ingreso' },
            { data: 'cantidad_de_piezas' },
            { data: 'clasificacion' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info btn-ver" data-id="${row.idPieza}" title="Ver Detalle">
                            <i class="bi bi-eye"></i> Ver
                        </button>
                        <button class="btn btn-sm btn-primary btn-editar" data-id="${row.idPieza}" title="Editar">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="${row.idPieza}" title="Eliminar">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ],
        pageLength: 5,
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Imprimir',
                className: 'btn btn-secondary'
            }
        ]
    });

    // Renderizar estadísticas
    if (window.datosPieza && Array.isArray(window.datosPieza)) {
        renderPiezaStats(window.datosPieza);
    }
});


