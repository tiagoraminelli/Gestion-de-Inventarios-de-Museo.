<?php 
require_once __DIR__ . '../../Controller/PiezaController.php'; 


use Controller\PiezaController;

$controller = new PiezaController();
$pieza = null;

if (isset($_GET['idPieza'])) {
    $pieza = $controller->getPiezaById($_GET['idPieza']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detalle de Pieza</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../public/css/nav-list.css">
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
    .detalle-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<?php include '../public/html/nav-list.html'; ?>

<div class="container my-5">
  <div class="card p-4">
    <h1 class="text-center mb-4">Detalle de la Pieza</h1>

    <?php if ($pieza): ?>
      <div class="row">
      <div class="col-md-4 text-center">
  <?php 
    $imagen = htmlspecialchars($pieza['imagen']);
    $rutaImagen = "../../public/img/" . $imagen;
    if (!empty($imagen) && file_exists($rutaImagen)): 
  ?>
    <img src="<?php echo $rutaImagen; ?>" alt="Imagen de pieza" class="img-fluid rounded shadow-sm">
  <?php else: ?>
    <div class="alert alert-warning">
      No hay imagen cargada para esta pieza.
    </div>
    <form action="../Controller/PiezaController.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="idPieza" value="<?php echo $pieza['idPieza']; ?>">
      <label for="imagen" class="btn btn-primary">
        <i class="bi bi-upload"></i> Subir Imagen
        <input type="file" name="imagen" id="imagen" hidden onchange="this.form.submit()">
      </label>
    </form>
  <?php endif; ?>
</div>

        <div class="col-md-8">
          <div class="detalle-item"><strong>ID:</strong> <?php echo $pieza['idPieza']; ?></div>
          <div class="detalle-item"><strong>Inventario:</strong> <?php echo htmlspecialchars($pieza['num_inventario']); ?></div>
          <div class="detalle-item"><strong>Especie:</strong> <?php echo htmlspecialchars($pieza['especie']); ?></div>
          <div class="detalle-item"><strong>Estado de Conservación:</strong> <?php echo htmlspecialchars($pieza['estado_conservacion']); ?></div>
          <div class="detalle-item"><strong>Fecha de Ingreso:</strong> <?php echo htmlspecialchars($pieza['fecha_ingreso']); ?></div>
          <div class="detalle-item"><strong>Cantidad de Piezas:</strong> <?php echo htmlspecialchars($pieza['cantidad_de_piezas']); ?></div>
          <div class="detalle-item"><strong>Clasificación:</strong> <?php echo htmlspecialchars($pieza['clasificacion']); ?></div>
          <div class="detalle-item"><strong>Observación:</strong> <?php echo htmlspecialchars($pieza['observacion']); ?></div>
          <div class="detalle-item"><strong>ID Donante:</strong> <?php echo htmlspecialchars($pieza['Donante_idDonante']); ?></div>
          <div class="detalle-item"><strong>Creado el:</strong> <?php echo htmlspecialchars($pieza['created_at']); ?></div>
          <div class="detalle-item"><strong>Actualizado el:</strong> <?php echo htmlspecialchars($pieza['updated_at']); ?></div>
        </div>
      </div>
    <?php else: ?>
      <div class="alert alert-danger">No se encontró la pieza solicitada.</div>
    <?php endif; ?>
    <a href="./part/pieza.php" class="btn btn-secondary mt-4"><i class="bi bi-arrow-left"></i> Volver al listado</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
