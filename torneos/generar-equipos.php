<?php
header("Content-Type: application/json");

require '../db/db.php';

if (!isset($_GET['torneo_id'])) {
    echo json_encode(['error' => 'ID del torneo requerido']);
    exit;
}

$torneo_id = intval($_GET['torneo_id']);

if ($torneo_id <= 0) {
    echo json_encode(['error' => 'ID del torneo inválido']);
    exit;
}

// Eliminar equipos anteriores (opcional)
$conn->query("DELETE FROM equipos WHERE torneo_id = $torneo_id");

// Obtener jugadores asociados al torneo
$sql = "
  SELECT j.id, j.nombre, j.apellido 
  FROM torneos_jugadores tj
  JOIN jugadores j ON tj.jugador_id = j.id
  WHERE tj.torneo_id = ?
  ORDER BY j.apellido ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $torneo_id);
$stmt->execute();
$result = $stmt->get_result();

$jugadores = [];
while ($row = $result->fetch_assoc()) {
    $jugadores[] = $row;
}

if (count($jugadores) < 2 || count($jugadores) % 2 !== 0) {
    echo json_encode(['error' => "Número inválido de jugadores: " . count($jugadores)]);
    exit;
}

// Generar equipos de 2 jugadores
$equipos = [];

foreach ($jugadores as $index => $jugador) {
    if ($index % 2 === 0) {
        $equipos[] = [
            'id' => count($equipos) + 1,
            'jugador1' => $jugadores[$index],
            'jugador2' => $jugadores[$index + 1] ?? null
        ];
    }
}

/// Eliminar equipos anteriores (opcional)
$stmtDeleteEquipos = $conn->prepare("DELETE FROM equipos WHERE torneo_id = ?");
$stmtDeleteEquipos->bind_param("i", $torneo_id);
$stmtDeleteEquipos->execute();

$stmtDeleteJugadores = $conn->prepare("DELETE FROM equipos_jugadores WHERE equipo_id IN (SELECT id FROM equipos WHERE torneo_id = ?)");
$stmtDeleteJugadores->bind_param("i", $torneo_id);
$stmtDeleteJugadores->execute();

// Guardar nuevos equipos
foreach ($equipos as $equipo) {
    // Insertar equipo
    $stmtInsertEquipo = $conn->prepare("INSERT INTO equipos (torneo_id) VALUES (?)");
    $stmtInsertEquipo->bind_param("i", $torneo_id);
    $stmtInsertEquipo->execute();
    $equipo_id = $stmtInsertEquipo->insert_id;

    // Insertar jugadores en el equipo
    foreach ([1, 2] as $posicion) {
        $jugador = $posicion === 1 ? $equipo['jugador1'] : $equipo['jugador2'];
        if (!$jugador) continue;

        $stmtInsertJugador = $conn->prepare("INSERT INTO equipos_jugadores (equipo_id, jugador_id, orden) VALUES (?, ?, ?)");
        $stmtInsertJugador->bind_param("iii", $equipo_id, $jugador['id'], $posicion);
        $stmtInsertJugador->execute();
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Equipos guardados correctamente',
    'equipos' => $equipos
]);
?>