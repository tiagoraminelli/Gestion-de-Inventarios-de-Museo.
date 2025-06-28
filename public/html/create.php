<?php
// Ejemplo: cargar la pieza para edición (null si es creación)
// $pieza = obtenerPiezaPorId($idPieza) o null
$accion = isset($pieza) ? 'updatePieza' : 'createPieza';
?>

<!-- Modal -->
<div class="modal fade" id="modalCrearPieza" tabindex="-1" aria-labelledby="modalCrearPiezaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" enctype="multipart/form-data" action="pieza.php">
      <input type="hidden" name="action" value="<?= $accion ?>">
      <?php if ($accion === 'updatePieza'): ?>
        <input type="hidden" name="idPieza" value="<?= htmlspecialchars($pieza['idPieza']) ?>">
      <?php endif; ?>

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?= $accion === 'createPieza' ? 'Crear Nueva Pieza' : 'Editar Pieza' ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">

            <!-- Número de Inventario -->
            <div class="col-md-6">
              <label class="form-label">Número de Inventario</label>
              <input type="text" class="form-control" name="num_inventario" required
                value="<?= isset($pieza) ? htmlspecialchars($pieza['num_inventario']) : '' ?>">
            </div>

            <!-- Especie -->
            <div class="col-md-6">
              <label class="form-label">Especie</label>
              <input type="text" class="form-control" name="especie" required
                value="<?= isset($pieza) ? htmlspecialchars($pieza['especie']) : '' ?>">
            </div>

            <!-- Estado de Conservación -->
            <div class="col-md-6">
              <label class="form-label">Estado de Conservación</label>
              <input type="text" class="form-control" name="estado_conservacion" required
                value="<?= isset($pieza) ? htmlspecialchars($pieza['estado_conservacion']) : '' ?>">
            </div>

            <!-- Fecha de Ingreso -->
            <div class="col-md-6">
              <label class="form-label">Fecha de Ingreso</label>
              <input type="date" class="form-control" name="fecha_ingreso" required
                value="<?= isset($pieza) ? htmlspecialchars($pieza['fecha_ingreso']) : '' ?>">
            </div>

            <!-- Cantidad de Piezas -->
            <div class="col-md-6">
              <label class="form-label">Cantidad de Piezas</label>
              <input type="text" class="form-control" name="cantidad_de_piezas" required
                value="<?= isset($pieza) ? htmlspecialchars($pieza['cantidad_de_piezas']) : '' ?>">
            </div>

            <!-- Clasificación -->
            <div class="col-md-6 mb-3">
              <label for="clasificacion" class="form-label">Clasificación</label>
              <select id="clasificacion" name="clasificacion" class="form-select" required>
                <option value="">Seleccione una clasificación</option>
                <?php
                $clases = ['Paleontología', 'Osteología', 'Ictiología', 'Geología', 'Botánica', 'Zoología', 'Arqueología', 'Octología'];
                foreach ($clases as $clase) {
                  $selected = (isset($pieza) && $pieza['clasificacion'] === $clase) ? 'selected' : '';
                  echo "<option value=\"$clase\" $selected>$clase</option>";
                }
                ?>
              </select>
            </div>
            <!-- Contenedor para campos de clasificación -->
            <div class="col-12">
              <div id="camposClasificacion" class="row g-3 mt-3"></div>
            </div>

            <!-- Observación -->
            <div class="col-12">
              <label class="form-label">Observación</label>
              <textarea class="form-control" name="observacion" rows="3"><?= isset($pieza) ? htmlspecialchars($pieza['observacion']) : '' ?></textarea>
            </div>

            <!-- Donante -->
            <div class="col-md-12 mb-3">
              <label for="donante" class="form-label">Donante</label>
              <select id="donante" name="donante" class="form-select select2-donante" style="width: 100%;"
                <?= (isset($pieza) && isset($pieza['nuevo_donante']) && $pieza['nuevo_donante']) ? 'disabled' : 'required' ?>>
                <option value="">Seleccione un donante</option>
                <?php
                require_once '../../Controller/DonadorController.php';

                use Controller\DonanteController;

                $donadorController = new DonanteController();
                $donantes = $donadorController->buscarDonantes();

                foreach ($donantes as $donante) {
                  $selected = (isset($pieza) && $pieza['Donante_idDonante'] == $donante['idDonante']) ? 'selected' : '';
                  echo "<option value='{$donante['idDonante']}' $selected>{$donante['nombre']} {$donante['apellido']}</option>";
                }
                ?>
              </select>
            </div>

            <!-- Switch para agregar nuevo donador -->
            <div class="form-check form-switch mt-2">
              <?php
              $nuevoDonanteChecked = (isset($pieza) && isset($pieza['nuevo_donante']) && $pieza['nuevo_donante']) ? 'checked' : '';
              ?>
              <input class="form-check-input" type="checkbox" id="toggleNuevoDonador" <?= $nuevoDonanteChecked ?>>
              <label class="form-check-label" for="toggleNuevoDonador">Agregar nuevo donador</label>
            </div>

            <!-- Campos para nuevo donador -->
            <div id="nuevoDonadorContainer" class="row g-2 mt-2 <?= $nuevoDonanteChecked ? '' : 'd-none' ?>">
              <div class="col-md-6">
                <label class="form-label">Nombre del Donador</label>
                <input type="text" class="form-control" name="nuevo_nombre"
                  value="<?= isset($pieza) && $nuevoDonanteChecked ? htmlspecialchars($pieza['nuevo_nombre'] ?? '') : '' ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Apellido del Donador</label>
                <input type="text" class="form-control" name="nuevo_apellido"
                  value="<?= isset($pieza) && $nuevoDonanteChecked ? htmlspecialchars($pieza['nuevo_apellido'] ?? '') : '' ?>">
              </div>
            </div>

            <!-- Imagen -->
            <div class="col-md-6 text-center">
              <label class="form-label">Imagen de la Pieza</label>
              <div class="d-flex flex-column align-items-center">
                <i class="bi bi-upload display-5 text-secondary mb-2"></i>
                <input type="file" class="form-control" name="imagen" accept="image/*">
                <?php if (isset($pieza) && !empty($pieza['imagen'])): ?>
                  <small>Imagen actual: <?= htmlspecialchars($pieza['imagen']) ?></small>
                <?php endif; ?>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><?= $accion === 'createPieza' ? 'Guardar Pieza' : 'Actualizar Pieza' ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const selectClasificacion = document.getElementById('clasificacion');
  const contenedorCampos = document.getElementById('camposClasificacion');

  selectClasificacion.addEventListener('change', function () {
    const valor = this.value;
    contenedorCampos.innerHTML = ''; // Limpia campos anteriores

    if (valor === 'Geología') {
      contenedorCampos.innerHTML = `
        <div class="col-md-6">
          <label for="tipo_rocas" class="form-label">Tipo de Rocas</label>
          <input type="text" class="form-control" id="tipo_rocas" name="tipo_rocas" required>
        </div>
        <div class="col-md-6">
          <label for="descripcion_geologia" class="form-label">Descripción</label>
          <textarea class="form-control" id="descripcion_geologia" name="descripcion_geologia" rows="3"></textarea>
        </div>
      `;
    }

    // Podés agregar más bloques para otras clasificaciones aquí...
    else if (valor === 'Paleontología') {
      contenedorCampos.innerHTML = `
        <div class="col-md-6">
          <label for="era" class="form-label">Era</label>
          <input type="text" class="form-control" name="era" id="era">
        </div>
        <div class="col-md-6">
          <label for="periodo" class="form-label">Período</label>
          <input type="text" class="form-control" name="periodo" id="periodo">
        </div>
        <div class="col-md-12">
          <label for="descripcion_paleo" class="form-label">Descripción</label>
          <textarea class="form-control" name="descripcion_paleo" id="descripcion_paleo" rows="3"></textarea>
        </div>
      `;
    }

    // Más casos según tus tablas...
  });
});
</script>
