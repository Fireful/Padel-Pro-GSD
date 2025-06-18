<?php
require '../db/db.php';

// Obtener ID del torneo desde GET
$torneo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($torneo_id <= 0) {
    die("ID de torneo inválido.");
}

// Obtener datos del torneo
$sql_torneo = "SELECT * FROM torneos WHERE id = ?";
$stmt_torneo = $conn->prepare($sql_torneo);
$stmt_torneo->bind_param("i", $torneo_id);
$stmt_torneo->execute();
$result_torneo = $stmt_torneo->get_result();

if ($result_torneo->num_rows === 0) {
    die("Torneo no encontrado.");
}

$torneo = $result_torneo->fetch_assoc();

// Obtener jugadores asociados
$sql_jugadores = "
  SELECT j.id, j.nombre, j.apellido 
  FROM torneos_jugadores tj
  JOIN jugadores j ON tj.jugador_id = j.id
  WHERE tj.torneo_id = ?
  ORDER BY j.apellido";

$stmt_jugadores = $conn->prepare($sql_jugadores);
$stmt_jugadores->bind_param("i", $torneo_id);
$stmt_jugadores->execute();
$result_jugadores = $stmt_jugadores->get_result();
?>

<?php include '../includes/header.php'; ?>

<h2 class="mb-4"><?= htmlspecialchars($torneo['nombre']) ?></h2>

<!-- Datos del torneo -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <strong>Categoría:</strong> <?= ucfirst($torneo['categoria']) ?>
      </div>
      <div class="col-md-6">
        <strong>Formato:</strong> <?= ucwords(str_replace('-', ' ', $torneo['formato'])) ?>
      </div>
      <div class="col-md-6">
        <strong>Fechas:</strong> <?= date('d/m/Y', strtotime($torneo['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($torneo['fecha_fin'])) ?>
      </div>
      <div class="col-md-6">
        <strong>Jugadores:</strong>
        <?= $torneo['max_participantes'] ?> máximos
      </div>
    </div>
  </div>
</div>

<!-- Jugadores inscritos -->
<h3 class="mb-3">Jugadores Inscritos</h3>

<?php if ($result_jugadores->num_rows > 0): ?>
  <div class="row g-3 mb-4" id="lista-jugadores">
    <?php while ($jugador = $result_jugadores->fetch_assoc()): ?>
      <div class="col-md-6">
        <div class="card border h-100 shadow-sm">
          <div class="card-body d-flex align-items-center">
            <i class="bi bi-person-circle text-primary me-3" style="font-size: 1.5rem;"></i>
            <span><?= htmlspecialchars($jugador['nombre'] . ' ' . $jugador['apellido']) ?></span>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <div class="alert alert-info mb-4">No hay jugadores inscritos aún.</div>
<?php endif; ?>

<!-- Botones -->
<div class="d-flex gap-2 mb-5">
  <a href="editar-torneo.php?id=<?= $torneo['id'] ?>" class="btn btn-outline-primary">
    <i class="bi bi-pencil-square"></i> Editar Torneo y Jugadores
  </a>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVerTorneo">
    <i class="bi bi-plus-lg"></i> Añadir Jugador
  </button>
  <button type="button" class="btn btn-success mb-4" id="btn-generar-equipos" data-torneo="<?= $torneo['id'] ?>">
  <i class="bi bi-shuffle"></i> Generar Equipos
</button>
<button type="button" class="btn btn-warning mb-4" id="btn-generar-partidos" data-torneo="<?= $torneo['id'] ?>">
  <i class="bi bi-calendar-check"></i> Generar Partidos
</button>

<a href="ver-partidos.php?torneo_id=<?= $torneo['id'] ?>" class="btn btn-info mb-4">
  <i class="bi bi-calendar-check"></i> Ver Partidos Guardados
</a>
</div>

<div id="contenedor-equipos" class="mb-5">
  <!-- Aquí aparecerán los equipos -->
</div>

<div id="contenedor-partidos" class="mb-5">
  <!-- Aquí aparecerán los partidos -->
</div>

<!-- Modal (copia del modal-ver-torneo.php) -->
<?php include '../modales/modal-ver-torneo.php'; ?>

<?php include '../includes/footer.php'; ?>