<?php 
// Llamar al controlador de la pieza
require_once __DIR__ . '/../../Controller/PiezaController.php'; 
require_once __DIR__ . '/../../Model/Pieza.php';
use Controller\PiezaController;
$Datos = new PiezaController();
$piezas = $Datos->allPiezas(); // Obtenemos los datos de las piezas como array

?>

<!DOCTYPE html> 
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tabla Pieza Cargadas en el Museo</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- CSS de Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

  <style>
    body {
      background-color: #f8f9fa;
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
  
  <div class="container my-5">
    <div class="card p-4">
      <h1 class="text-center mb-4">Tabla de Piezas</h1>
      
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
        <tbody>
          <!-- Se cargan dinámicamente -->
        </tbody>
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
      <h2 class="text-center mb-4">Análisis de Gráficos</h2>
      <?php include '../../public/html/charts.html'; ?>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../../components/chart.js"></script>

  <script>
    window.datosPieza = <?php echo json_encode($piezas); ?>;
    console.log(window.datosPieza);

    $(document).ready(function() {
      const tabla = $('#tablaPieza').DataTable({
        data: window.datosPieza,
        columns: [
          { data: null, render: function(data, type, row) {
            return `<input type="checkbox" class="check-item" data-id="${row.idPieza}">`;
          }},
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
        dom: 'Bfrtip',  // 👉 esta línea activa la barra de botones arriba
  buttons: [
    {
      extend: 'print',
      text: '<i class="bi bi-printer"></i> Imprimir',
      className: 'btn btn-secondary'
    }
  ]
      });

      // Puedes agregar eventos para botones aquí
      $('#tablaPieza').on('click', '.btn-ver', function() {
        const id = $(this).data('id');
        alert(`Ver detalle de la pieza ID ${id}`);
      });
      $('#tablaPieza').on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        alert(`Editar pieza ID ${id}`);
      });
      $('#tablaPieza').on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');
        eliminarPieza(id);

      });

    });
  </script>

  <script src="../../public/js/part/charts-part.js"></script>
  <!-- JS de eliminar -->
<script src="../../public/js/part/delete-part.js"></script>   
<script src="../../public/js/part/multi-delete-part.js"></script>   
<!-- JS de Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

</body>
</html>
