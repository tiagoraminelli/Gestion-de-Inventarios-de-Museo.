<?php

require_once __DIR__ . '/../Controller/PiezaController.php';
require_once __DIR__ . '/../Model/Pieza.php';
require_once __DIR__ . '/../Controller/DonadorController.php';

use Controller\DonanteController;
use Controller\PiezaController;

// datos del donante

$donanteCtrl = new DonanteController();
$donantes = $donanteCtrl->allDonantes();


// piezas

$Datos = new PiezaController();

// Parámetros de paginación y guardar en una variable de sesión
$porPagina = isset($_GET['porPagina']) ? intval($_GET['porPagina']) : 9;

$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $porPagina;

// Obtener total de piezas
$totalPiezas = $Datos->countPiezas();
$totalPaginas = ceil($totalPiezas / $porPagina);

$termino = $_GET['buscar'] ?? '';

if ($termino !== '') {
  // Si hay búsqueda
  $piezas = $Datos->buscarPiezas($termino, $porPagina, $offset);

  // Contar resultados filtrados
  $totalFiltrados = count($Datos->buscarPiezas($termino)); // sin limit/offset
  $totalPaginas = ceil($totalFiltrados / $porPagina);
} else {
  // Sin búsqueda, comportamiento normal
  $piezas = $Datos->getPiezasPaginadas($porPagina, $offset);
  $totalPiezas = $Datos->countPiezas();
  $totalPaginas = ceil($totalPiezas / $porPagina);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Piezas del Museo</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../public/css/nav-list.css">
  <link rel="stylesheet" href="../public/css/modal.css">
  <link rel="stylesheet" href="../public/css/style.css">




  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php include '../public/html/nav-list.html'; ?>

  <main class="container my-4">
    <!-- Header + botón de nueva pieza + buscador -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
      <h1 class="h3 mb-0">Piezas del Museo</h1>


      <!-- Contenedor de búsqueda -->
      <div class="d-flex gap-2">
        <!-- Botón para ajustar la cantidad de piezas por página -->
        <div class="btn-group">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $porPagina ?> por página
          </button>
          <ul class="dropdown-menu">
            <?php
            $opciones = [6, 9, 12, 15, 18, 24];
            foreach ($opciones as $opcion):
              $url = "?porPagina=$opcion";
              if ($termino !== '') $url .= "&buscar=" . urlencode($termino);
            ?>
              <li>
                <a class="dropdown-item <?= $porPagina == $opcion ? 'active' : '' ?>" href="<?= $url ?>">
                  <?= $opcion ?> por página
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <!-- Formulario de búsqueda y x para eliminar búsqueda -->
        <form method="GET" class="d-flex" action="">
          <input type="text" name="buscar" class="form-control" placeholder="Buscar pieza..." value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
          <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
          <?php if ($termino !== ''): ?>
            <a href="?porPagina=<?= $porPagina ?>" class="btn btn-outline-secondary ms-2">X</a>
          <?php endif; ?>
        </form>

        <!-- Botón para ocultar o mostrar el formulario de nueva pieza -->
        <button id="toggleFormCrear" class="btn btn-outline-primary">
          <i class="bi bi-plus-circle"></i> Formulario
        </button>



        <!-- Botón Nueva Pieza -->
        <button type="button" class="btn btn-primary">
          <a href="estadisticas.php" style="color: inherit; text-decoration: none;"><i class="bi-bar-chart"></i> Estadísticas</a>
        </button>
      </div>
    </div>

    <!-- Formulario de creación de nueva pieza -->
    <div id="containerFormCrear" class="mb-4" style="display: none;">
      <div class="my-4 mx-auto p-relative bg-white shadow-1" style="max-width: auto; border-radius: 12px; overflow: hidden;">
        <div class="px-4 py-4">
          <h2 class="ff-serif font-weight-normal text-black mb-3">Crear Nueva Pieza</h2>

          <form id="formCrearPieza" enctype="multipart/form-data">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Número de Inventario</label>
                <input type="text" class="form-control" name="num_inventario" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Especie</label>
                <input type="text" class="form-control" name="especie" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Estado de Conservación</label>
                <input type="textarea" class="form-control" name="estado_conservacion" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Fecha de Ingreso</label>
                <input type="date" class="form-control" name="fecha_ingreso" required>
              </div>
              <div class="col-md-3">
                <label for="donante" class="form-label">Donante</label>
                <select class="form-select" name="Donante_idDonante" id="donante" required>
                  <option value="" disabled selected>Selecciona un donante</option>
                  <?php foreach ($donantes as $donante): ?>
                    <option value="<?= $donante['idDonante'] ?>">
                      <?= htmlspecialchars($donante['nombre'] . ' ' . $donante['apellido']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Cantidad de Piezas</label>
                <input type="text" class="form-control" name="cantidad_de_piezas">
              </div>
              <div class="col-md-3">
                <label class="form-label">Clasificación</label>
                <select class="form-select" name="clasificacion">
                  <option value="Paleontologia">Paleontología</option>
                  <option value="Osteologia">Osteología</option>
                  <option value="Ictiologia">Ictiología</option>
                  <option value="Geologia">Geología</option>
                  <option value="Botanica">Botánica</option>
                  <option value="Zoologia">Zoología</option>
                  <option value="Arqueologia">Arqueología</option>
                  <option value="Octologia">Octología</option>
                </select>
              </div>

              <div class="col-12">
                <label class="form-label">Observación</label>
                <textarea class="form-control" name="observacion" rows="3"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Imagen</label>
                <input type="file" class="form-control" name="imagen">
              </div>
            </div>

            <!-- Botones al final del formulario, alineados a la derecha -->
            <div class="mt-3 d-flex justify-content-end gap-2">
              <button type="submit" class="btn btn-success">Crear Pieza</button>
              <button type="button" class="btn btn-secondary" id="btnLimpiar">Limpiar Campos</button>
            </div>



          </form>
        </div>
      </div>
    </div>

    <!-- Grid de piezas -->
    <div class="row g-3">
      <?php foreach ($piezas as $pieza): ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="my-2 mx-auto p-relative bg-white shadow-1 blue-hover" style="width: 360px; overflow: hidden; border-radius: 1px;">
            <?php $imgPath = "../assets/upload/placeholder.webp"; ?>
            <img src="<?= $imgPath ?>" class="d-block w-full" alt="<?= htmlspecialchars($pieza['especie']) ?>">


            <div class="card-body d-flex flex-column px-2 py-2">
              <!-- Clasificación -->
              <p class="mb-0 small font-weight-medium text-uppercase mb-1 text-muted lts-2px">
                <?= htmlspecialchars($pieza['clasificacion']) ?>
              </p>

              <!-- Número de ID -->
              <p class="mb-0 small font-weight-medium text-uppercase mb-1 text-muted lts-2px">
                ID: <?= htmlspecialchars($pieza['idPieza']) ?>
              </p>

              <!-- Título: especie -->
              <h1 class="ff-serif font-weight-normal text-black card-heading mt-0 mb-1" style="line-height: 1.25;">
                <?= htmlspecialchars($pieza['especie']) ?>
              </h1>

              <!-- Descripción: observación -->
              <p class="mb-2 text-muted">
                <?= htmlspecialchars($pieza['observacion']) ?>
              </p>

              <!-- Botones centrados -->
              <div class="mt-auto d-flex justify-content-center gap-2">
                <button class="btn btn-green btn-sm btn-ver " data-id="<?= $pieza['idPieza'] ?>">
                  <i class="bi bi-eye"></i>
                </button>

                <button class="btn btn-primary btn-sm btn-editar" data-id="<?= $pieza['idPieza'] ?>">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <button class="btn btn-danger btn-sm btn-eliminar" data-id="<?= $pieza['idPieza'] ?>">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>



    <!-- Paginación -->
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
          <a class="page-link" href="?pagina=<?= $pagina - 1 ?><?= $termino !== '' ? '&buscar=' . urlencode($termino) : '' ?>">Anterior</a>
        </li>
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
          <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
            <a class="page-link" href="?pagina=<?= $i ?><?= $termino !== '' ? '&buscar=' . urlencode($termino) : '' ?>"><?= $i ?></a>

          </li>
        <?php endfor; ?>
        <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
          <a class="page-link" href="?pagina=<?= $pagina + 1 ?><?= $termino !== '' ? '&buscar=' . urlencode($termino) : '' ?>">Siguiente</a>
        </li>
      </ul>
    </nav>
  </main>


  <!-- Scripts -->
  <script>
    window.datosPieza = <?= json_encode($piezas) ?>;
  </script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="../public/js/ver.js"></script>
  <script src="../public/js/editar.js"></script>
  <script src="../public/js/pieza.js"></script>
  <script src="../public/js/eliminar.js"></script>
  <script src="../public/js/crear.js"></script>
  <script src="../public/js/btnLimpiar.js"></script>

  
  <!-- Modal para ver pieza -->
  <div class="modal fade" id="modalVerPieza" tabindex="-1" aria-labelledby="modalVerPiezaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalVerPiezaLabel">Detalle de la Pieza</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="contenidoModalPieza">
          <!-- Aquí se cargan los datos con JS -->
          <div class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para editar pieza -->
  <div class="modal fade" id="modalEditarPieza" tabindex="-1" aria-labelledby="modalEditarPiezaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarPiezaLabel">Editar Pieza</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="contenidoModalEditarPieza">
          <!-- Aquí se cargan los datos con JS -->
          <div class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</body>
<script>
  $(document).ready(function() {
    $('#toggleFormCrear').click(function() {
      $('#containerFormCrear').slideToggle();
      // Cambiar texto del botón
      let btn = $(this);
      if ($('#containerFormCrear').is(':visible')) {
        btn.html('<i class="bi bi-dash-circle"></i> Ocultar Formulario');
      } else {
        btn.html('<i class="bi bi-plus-circle"></i> Formulario');
      }
    });
  });
</script>

</html>