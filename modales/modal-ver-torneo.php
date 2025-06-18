<!-- modales/modal-ver-torneo.php -->

<?php if (!isset($row)) { return; } // Evita errores si se accede directamente ?>

<div class="modal fade" id="modalTorneo<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-<?= $row['categoria'] === 'masculino' ? 'primary' : ($row['categoria'] === 'femenino' ? 'danger' : 'success') ?> text-white">
        <h5 class="modal-title"><?= htmlspecialchars($row['nombre']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- Datos del torneo -->
        <div class="mb-4">
          <h6 class="text-muted mb-3">Datos del Torneo</h6>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><i class="bi bi-person-badge me-2"></i>Categoría</span>
              <span class="badge bg-light text-dark rounded-pill"><?= ucfirst($row['categoria']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><i class="bi bi-calendar me-2"></i>Fechas</span>
              <span><?= date('d/m', strtotime($row['fecha_inicio'])) ?> - <?= date('d/m', strtotime($row['fecha_fin'])) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><i class="bi bi-clock me-2"></i>Formato</span>
              <span class="badge bg-secondary rounded-pill"><?= ucwords(str_replace('-', ' ', $row['formato'])) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span><i class="bi bi-people me-2"></i>Jugadores</span>
              <span class="badge bg-<?= ($row['num_jugadores'] >= $row['max_participantes']) ? 'success' : 'warning' ?> rounded-pill">
                <?= $row['num_jugadores'] ?> / <?= $row['max_participantes'] ?>
              </span>
            </li>
          </ul>
        </div>


        <!-- Formulario para añadir jugadores -->
<form id="form-add-jugador" class="mb-3">
  <input type="hidden" name="torneo_id" value="<?= $row['id'] ?>">
  <div class="input-group">
    <select name="jugador_id" id="select-jugador" class="form-select" required>
      <option value="">Selecciona un jugador...</option>
      <?php
      // Obtener todos los jugadores NO asociados
      $sql = "
        SELECT j.id, j.nombre, j.apellido 
        FROM jugadores j
        LEFT JOIN torneos_jugadores tj ON j.id = tj.jugador_id AND tj.torneo_id = ?
        WHERE tj.jugador_id IS NULL
        ORDER BY j.apellido";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $row['id']);
      $stmt->execute();
      $resultJugadores = $stmt->get_result();

      while ($jugador = $resultJugadores->fetch_assoc()):
      ?>
        <option value="<?= $jugador['id'] ?>"><?= htmlspecialchars($jugador['apellido'] . ', ' . $jugador['nombre']) ?></option>
      <?php endwhile; ?>
    </select>
    <button class="btn btn-outline-primary" type="submit">Añadir</button>
  </div>
</form>
<div id="mensaje-jugador" class="d-none mt-2 mb-2"></div>



        <!-- Lista de jugadores -->
        <div class="lista-jugadores row g-2">
          <h6 class="text-muted mb-3">Jugadores Inscritos</h6>
          <?php
          // Obtener jugadores asociados a este torneo
          require 'db/db.php';
          $jugadores_sql = "
            SELECT j.id, j.nombre, j.apellido 
            FROM torneos_jugadores tj
            JOIN jugadores j ON tj.jugador_id = j.id
            WHERE tj.torneo_id = ?
            ORDER BY j.apellido";

          $jugadores_stmt = $conn->prepare($jugadores_sql);
          $jugadores_stmt->bind_param("i", $row['id']);
          $jugadores_stmt->execute();
          $jugadores_result = $jugadores_stmt->get_result();

          if ($jugadores_result->num_rows > 0):
            while ($jugador = $jugadores_result->fetch_assoc()):
          ?>
          <div class="col-md-6">
                  <div class="card border rounded-3 h-100">
                    <div class="card-body d-flex align-items-center">
                      <div class="flex-shrink-0 me-3">
                        <i class="bi bi-person-circle text-primary" style="font-size: 2rem;"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0"><?= htmlspecialchars($jugador['apellido'] . ', ' . $jugador['nombre']) ?></h6>
                      </div>
                    </div>
                  </div>
                </div>
          <?php endwhile; else: ?>
            <div class="alert alert-info py-2">No hay jugadores inscritos aún.</div>
          <?php endif; ?>
        </div>

        <!-- Botón Editar -->
        <div class="mt-4 text-end">
          <a href="torneos/editar-torneo.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil-square"></i> Editar Torneo y Jugadores
          </a>
        </div>
      </div>
    </div>
  </div>
</div>