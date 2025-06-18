<!-- editar-torneo.php -->

<?php
require '../db/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM torneos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Torneo no encontrado.");
}

$torneo = $result->fetch_assoc();

// Obtener todos los jugadores
$jugadores_sql = "SELECT id, nombre, apellido FROM jugadores ORDER BY apellido ASC";
$jugadores_result = $conn->query($jugadores_sql);

// Obtener jugadores ya asociados a este torneo
$asociados_sql = "SELECT jugador_id FROM torneos_jugadores WHERE torneo_id = ?";
$asociados_stmt = $conn->prepare($asociados_sql);
$asociados_stmt->bind_param("i", $id);
$asociados_stmt->execute();
$asociados_result = $asociados_stmt->get_result();

$jugadores_asociados = [];
while ($row = $asociados_result->fetch_assoc()) {
    $jugadores_asociados[] = $row['jugador_id'];
}
?>

<?php include '../includes/header.php'; ?>
      
    </div>
    <div class="card-body">

      <form method="POST" action="actualizar-torneo.php" class="formulario d-flex row g-3 needs-validation" novalidate>
<h2 class="text-primary mb-0">Editar Torneo</h4>
        <input type="hidden" name="id" value="<?= $torneo['id'] ?>">

        <!-- Campo Nombre -->
        <div class="col-md-12">
          <label for="nombre" class="form-label">Nombre del Torneo</label>
          <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($torneo['nombre']) ?>" required>
        </div>

        <!-- Campo Categoría -->
        <div class="col-md-6">
          <label for="categoria" class="form-label">Categoría</label>
          <select name="categoria" class="form-select" required>
            <option value="masculino" <?= $torneo['categoria'] === 'masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="femenino" <?= $torneo['categoria'] === 'femenino' ? 'selected' : '' ?>>Femenino</option>
            <option value="mixto" <?= $torneo['categoria'] === 'mixto' ? 'selected' : '' ?>>Mixto</option>
          </select>
        </div>

        <!-- Campo Formato -->
        <div class="col-md-6">
          <label for="formato" class="form-label">Formato</label>
          <select name="formato" class="form-select" required>
            <option value="liguilla" <?= $torneo['formato'] === 'liguilla' ? 'selected' : '' ?>>Liguilla</option>
            <option value="eliminatoria" <?= $torneo['formato'] === 'eliminatoria' ? 'selected' : '' ?>>Eliminatoria</option>
            <option value="round-robin" <?= $torneo['formato'] === 'round-robin' ? 'selected' : '' ?>>Round Robin</option>
          </select>
        </div>

        <!-- Campo Participantes -->
        <div class="col-md-6">
          <label for="max_participantes" class="form-label">Máximo de Participantes</label>
          <input type="number" class="form-control" name="max_participantes" value="<?= $torneo['max_participantes'] ?>" min="2" required>
        </div>

        <!-- Campo Jugadores -->
        <div class="col-md-12">
          <label for="jugadores" class="form-label">Seleccionar Jugadores</label>
          <select name="jugadores[]" id="jugadores" class="form-select" size="8" multiple required>
            <?php while ($jugador = $jugadores_result->fetch_assoc()): ?>
              <option value="<?= $jugador['id'] ?>" <?= in_array($jugador['id'], $jugadores_asociados) ? 'selected' : '' ?>>
                <?= htmlspecialchars($jugador['apellido'] . ', ' . $jugador['nombre']) ?>
              </option>
            <?php endwhile; ?>
          </select>
          <small class="text-muted">Puedes seleccionar más de uno manteniendo pulsado Ctrl (Windows) o Cmd (Mac)</small>
        </div>

        <!-- Botón -->
        <div class="col-12 d-grid mt-3">
          <button type="submit" class="btn btn-primary">Actualizar Torneo</button>
        </div>

      </form>

    

<?php include '../includes/footer.php'; ?>