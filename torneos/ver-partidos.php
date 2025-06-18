<?php
require '../db/db.php';

if (!isset($_GET['torneo_id'])) {
    die("ID del torneo requerido");
}

$torneo_id = intval($_GET['torneo_id']);

// Obtener datos del torneo
$stmt_torneo = $conn->prepare("SELECT nombre FROM torneos WHERE id = ?");
$stmt_torneo->bind_param("i", $torneo_id);
$stmt_torneo->execute();
$result_torneo = $stmt_torneo->get_result();

if ($result_torneo->num_rows === 0) {
    die("Torneo no encontrado");
}

$torneo = $result_torneo->fetch_assoc();
$stmt_torneo->close(); // Cerrar statement usado
?>

<?php include '../includes/header.php'; ?>

<h2>Partidos - <?= htmlspecialchars($torneo['nombre']) ?></h2>

<?php
// Obtener partidos con sus equipos y jugadores
$sql_partidos = "
  SELECT p.id AS partido_id, pe.equipo_id
  FROM partidos p
  JOIN partidos_equipos pe ON p.id = pe.partido_id
  WHERE p.torneo_id = ?
  ORDER BY p.id";

$stmt_partidos = $conn->prepare($sql_partidos);
$stmt_partidos->bind_param("i", $torneo_id);
$stmt_partidos->execute();
$result_partidos = $stmt_partidos->get_result();

$partido_equipos = [];

while ($row = $result_partidos->fetch_assoc()) {
    $partido_id = $row['partido_id'];
    if (!isset($partido_equipos[$partido_id])) {
        $partido_equipos[$partido_id] = ['id' => $partido_id, 'equipos' => []];
    }
    $partido_equipos[$partido_id]['equipos'][] = $row['equipo_id'];
}

$partidos = [];

foreach ($partido_equipos as $p) {
    $partido_id = $p['id'];
    $equipos_ids = $p['equipos'];

    $sql_equipos_jugadores = "
  SELECT 
    j.id AS jugador_id,
    j.nombre,
    j.apellido,
    ej.equipo_id
  FROM jugadores j
  JOIN equipos_jugadores ej ON j.id = ej.jugador_id
  WHERE ej.equipo_id IN (" . implode(',', array_map('intval', $equipos_ids)) . ")
  ORDER BY ej.orden";

    $result_ej = $conn->query($sql_equipos_jugadores);

    $equipos = [];

    while ($jugador = $result_ej->fetch_assoc()) {
        $equipo_id = $jugador['equipo_id'];
        if (!isset($equipos[$equipo_id])) {
            $equipos[$equipo_id] = [];
        }
        $equipos[$equipo_id][] = [
            'nombre' => $jugador['nombre'],
            'apellido' => $jugador['apellido']
        ];
    }

    // Añadir partido con todos sus equipos y jugadores
    $partidos[] = [
        'id' => $partido_id,
        'equipos' => array_values($equipos)
    ];
}

if (count($partidos) > 0): ?>
  <div class="row g-3 mb-4">
    <?php foreach ($partidos as $partido): ?>
      <div class="col-md-6">
        <div class="card border rounded shadow-sm p-3">
          <h5 class="card-title">Partido #<?= $partido['id'] ?></h5>
          <?php foreach ($partido['equipos'] as $index => $equipo): ?>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <strong>Equipo <?= $index + 1 ?>:</strong><br>
                <?php foreach ($equipo as $jugador): ?>
                  <?= htmlspecialchars($jugador['apellido'] . ', ' . $jugador['nombre']) ?><br>
                <?php endforeach; ?>
              </li>
            </ul>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="alert alert-info">No hay partidos generados aún.</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>