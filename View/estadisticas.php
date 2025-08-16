<?php
require_once __DIR__ . '/../Controller/PiezaController.php';
require_once __DIR__ . '/../Controller/DonadorController.php';

use Controller\DonanteController;
use Controller\PiezaController;

$piezaCtrl = new PiezaController();
$donanteCtrl = new DonanteController();

// Datos principales
$topDonantes = array_slice($donanteCtrl->getPiezasPorDonante(), 0, 5, true);
$piezasPorClasificacion = $piezaCtrl->getPiezasPorClasificacion();
$piezasPorDonante = $donanteCtrl->getPiezasPorDonante();
$ultimasPiezas = $piezaCtrl->getUltimasPiezasCreadas(5);
$ultimasPiezasActualizadas = $piezaCtrl->getUltimasPiezasActualizadas(5);
$totalPiezas = $piezaCtrl->countPiezas();
$totalDonantes = $donanteCtrl->countDonantes();
$totalClasificaciones = count($piezasPorClasificacion);
$piezasPorAno = $piezaCtrl->getPiezasPorAno();
$piezasPorEstado = $piezaCtrl->getPiezasPorEstado();

function renderChart($id, $type, $labels, $data, $colors = [], $options = []) {
    $labelsJSON = json_encode($labels);
    $dataJSON = json_encode($data);
    $colorsJSON = json_encode($colors ?: ['rgba(54, 162, 235, 0.6)']); // <-- default si no hay colores
    $optionsJSON = json_encode($options ?: new stdClass()); // <-- opciones por defecto
    return <<<HTML
<canvas id="{$id}"></canvas>
<script>
  new Chart(document.getElementById('{$id}').getContext('2d'), {
    type: '{$type}',
    data: {
      labels: {$labelsJSON},
      datasets: [{
        data: {$dataJSON},
        backgroundColor: {$colorsJSON}
      }]
    },
    options: {$optionsJSON}
  });
</script>
HTML;
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estadísticas de Piezas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../public/css/nav-list.css">
  <link rel="stylesheet" href="../public/css/style.css">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    h1, h2 { color: #343a40; }
    .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .card-title { font-weight: 500; margin-bottom: 1rem; color: #495057; }
    canvas { padding: 10px; }
    footer { margin-top: 3rem; padding: 1.5rem 0; background-color: #343a40; color: #fff; text-align: center; }
  </style>
</head>
<body>
  <?php include '../public/html/nav-list.html'; ?>

  <!-- Resumen rápido -->
  <div class="container my-5">
    <h2 class="mb-4 text-center">Resumen Rápido</h2>
    <div class="row text-center g-4 mb-5">
      <?php foreach ([
          ['Total de Piezas', $totalPiezas],
          ['Total de Donantes', $totalDonantes],
          ['Clasificaciones Diferentes', $totalClasificaciones],
          ['Total Piezas Catalogadas', array_sum($piezasPorEstado)]
      ] as $card): ?>
      <div class="col-md-3">
        <div class="card p-4 shadow-sm">
          <h3><?= $card[1] ?></h3>
          <p><?= $card[0] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Últimas piezas -->
    <h2 class="mb-3">Últimas 5 Piezas Subidas</h2>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Nombre / Especie</th>
            <th>Clasificación</th>
            <th>Donante</th>
            <th>Fecha de Ingreso</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ultimasPiezas as $index => $pieza): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($pieza['especie']) ?></td>
            <td><?= htmlspecialchars($pieza['clasificacion']) ?></td>
            <td><?= htmlspecialchars($pieza['Donante_idDonante']) ?></td>
            <td><?= htmlspecialchars($pieza['fecha_ingreso']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>



  <!-- Últimas Piezas Actualizadas -->
  <div class="container my-5">
    <h2 class="mb-4 text-center">Últimas Piezas Actualizadas</h2>
    <div class="row">
      <?php foreach ($ultimasPiezasActualizadas as $pieza): ?>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($pieza['especie']) ?></h5>
            <p class="card-text">Clasificación: <?= htmlspecialchars($pieza['clasificacion']) ?></p>
            <p class="card-text">Donante: <?= htmlspecialchars($pieza['Donante_idDonante']) ?></p>
            <p class="card-text">Fecha de Ingreso: <?= htmlspecialchars($pieza['fecha_ingreso']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Charts -->
  <div class="container my-5">
    <h1 class="mb-4 text-center">Estadísticas de Piezas</h1>
    <div class="mb-4 text-center">
      <a href="#" class="btn btn-primary">Exportar a PDF</a>
      <a href="#" class="btn btn-secondary">Exportar a Excel</a>
    </div>
    <div class="row g-4">
      <div class="col-md-6 card p-4 mb-4">
        <h5 class="card-title">Piezas por Clasificación</h5>
        <?= renderChart('chartClasificacion', 'bar', array_keys($piezasPorClasificacion), array_values($piezasPorClasificacion)) ?>
      </div>
      <div class="col-md-6 card p-4 mb-4">
        <h5 class="card-title">Piezas por Donante</h5>
        <?= renderChart('chartDonante', 'pie', array_keys($piezasPorDonante), array_values($piezasPorDonante), ['#007bff','#28a745','#dc3545','#ffc107','#17a2b8','#6f42c1','#fd7e14','#20c997']) ?>
      </div>
      <div class="col-md-6 card p-4 mb-4">
        <h5 class="card-title">Piezas por Año de Ingreso</h5>
        <?= renderChart('chartAno', 'line', array_keys($piezasPorAno), array_values($piezasPorAno), [], ['responsive' => true]) ?>
      </div>
      <div class="col-md-6 card p-4 mb-4">
        <h5 class="card-title">Piezas por Estado de Conservación</h5>
        <?= renderChart('chartEstado', 'bar', array_keys($piezasPorEstado), array_values($piezasPorEstado), [], ['indexAxis' => 'y', 'responsive' => true]) ?>
      </div>
      <div class="col-12 card p-4 mb-4">
        <h5 class="card-title">Top Donantes (Más piezas donadas)</h5>
        <?= renderChart('chartTopDonantes', 'bar', array_keys($topDonantes), array_values($topDonantes)) ?>
      </div>
    </div>
  </div>

  <footer>
    &copy; <?= date('Y') ?> Museo. Todos los derechos reservados.
  </footer>
</body>
</html>
