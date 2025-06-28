<?php 
require_once __DIR__ . '/../../Controller/PiezaController.php'; 
require_once __DIR__ . '/../../Model/Pieza.php';

use Controller\PiezaController;

$Datos = new PiezaController();
$piezas = $Datos->allPiezas();
?>

<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tabla Pieza Cargadas en el Museo</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../public/css/nav-list.css">

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background-color: #fff;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    h1, h2 {
      font-weight: bold;
    }
    .btn-sm i {
      margin-right: 4px;
    }
  </style>
</head>
<body>

  <?php include '../../public/html/nav-list.html'; ?>

  <div class="container my-5">
    <div class="card p-4">
      <h1 class="text-center mb-4">Tabla de Piezas</h1>
      <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearPieza">
        <i class="bi bi-plus-circle"></i> Nueva Pieza
      </button>

      <table id="tablaPieza" class="table table-striped table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th><input type="checkbox" id="checkAll"></th>
            <th>ID</th>
            <th>Inventario</th>
            <th>Especie</th>
            <th>Estado</th>
            <th>Fecha Ingreso</th>
            <th>Cantidad</th>
            <th>Clasificación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

      <div class="container my-3">
        <button id="btnEliminarSeleccionados" class="btn btn-danger"> 
          <i class="bi bi-trash"></i> Eliminar Seleccionados
        </button>
      </div>
    </div>
  </div>

  <div class="container my-5">
    <div class="card p-4">
      <div class="row mb-4" id="estadisticas-piezas"></div>
    </div>
  </div>

  <div class="container my-5">
    <div class="card p-4">
      <h2 class="text-center mb-4">Análisis de Gráficos</h2>
      <?php include '../../public/html/charts.html'; ?>
    </div>
  </div>

  <!-- Modales -->
  <?php include '../../public/html/create.php'; ?>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables y Buttons -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

  <!-- Componentes propios -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../../components/chart.js"></script>
  <script src="../../components/card.js"></script>
  <script src="../../public/js/part/search-donate.js"></script>
  <script src="../../public/js/part/delete-part.js"></script>   
  <script src="../../public/js/part/multi-delete-part.js"></script>   


  <script>
    window.datosPieza = <?php echo json_encode($piezas); ?>;


    $(document).ready(function() {
      const tabla = $('#tablaPieza').DataTable({
        data: window.datosPieza,
        columns: [
          {
            data: null,
            render: function(data, type, row) {
              return `<input type="checkbox" class="check-item" data-id="${row.idPieza}">`;
            }
          },
          { data: 'idPieza' },
          { data: 'num_inventario' },
          { data: 'especie' },
          { data: 'estado_conservacion' },
          { data: 'fecha_ingreso' },
          { data: 'cantidad_de_piezas' },
          { data: 'clasificacion' },
          {
            data: null,
            render: function(data, type, row) {
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

      $('#tablaPieza').on('click', '.btn-ver', function() {
        const id = $(this).data('id');
        window.location.href = `../../Controller/PiezaController.php?accion=ver&idPieza=${id}`;
      });

      $('#tablaPieza').on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        window.location.href = `../../Controller/PiezaController.php?accion=obtener&idPieza=${id}`;
      });

      $('#tablaPieza').on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');
        eliminarPieza(id);
      });
    });

    if (window.datosPieza && Array.isArray(window.datosPieza)) {
      renderPiezaStats(window.datosPieza);
    }
  </script>
  <script src="../../public/js/part/charts-part.js"></script>



  <!-- CSS de Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- JS de Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$('#tablaPieza').on('click', '.btn-editar', function() {
  const id = $(this).data('id');

  $.ajax({
    url: '../../Controller/PiezaController.php',
    method: 'GET',
    data: { accion: 'obtener', idPieza: id },
    dataType: 'json',
    success: function(data) {
      // Aquí carga los datos en el formulario
      // Por ejemplo, supongamos que el formulario está en un modal con id #modalEditarPieza

      $('#modalEditarPieza input[name="idPieza"]').val(data.idPieza);
      $('#modalEditarPieza input[name="num_inventario"]').val(data.num_inventario);
      $('#modalEditarPieza input[name="especie"]').val(data.especie);
      $('#modalEditarPieza input[name="estado_conservacion"]').val(data.estado_conservacion);
      $('#modalEditarPieza input[name="fecha_ingreso"]').val(data.fecha_ingreso);
      $('#modalEditarPieza input[name="cantidad_de_piezas"]').val(data.cantidad_de_piezas);
      $('#modalEditarPieza select[name="clasificacion"]').val(data.clasificacion).trigger('change');
      $('#modalEditarPieza textarea[name="observacion"]').val(data.observacion);

      // Abrir el modal (si usas Bootstrap 5)
      const modal = new bootstrap.Modal(document.getElementById('modalEditarPieza'));
      modal.show();
    },
    error: function() {
      alert('Error al obtener los datos de la pieza');
    }
  });
});

</script>
</body>
</html>
