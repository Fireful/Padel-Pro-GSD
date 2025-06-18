<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../db/db.php';

if (!isset($_GET['torneo_id'])) {
    echo json_encode(['error' => 'ID del torneo requerido']);
    exit;
}

$torneo_id = intval($_GET['torneo_id']);

// Obtener formato del torneo
$sql_torneo = "SELECT formato FROM torneos WHERE id = ?";
$stmt_torneo = $conn->prepare($sql_torneo);
$stmt_torneo->bind_param("i", $torneo_id);
$stmt_torneo->execute();
$result_torneo = $stmt_torneo->get_result();

if ($result_torneo->num_rows === 0) {
    echo json_encode(['error' => 'Torneo no encontrado']);
    exit;
}

$torneo = $result_torneo->fetch_assoc();
$formato = $torneo['formato'];

// Eliminar partidos anteriores (opcional)
$stmt_delete = $conn->prepare("DELETE pe.*, p.* FROM partidos_equipos pe INNER JOIN partidos p ON pe.partido_id = p.id WHERE p.torneo_id = ?");
$stmt_delete->bind_param("i", $torneo_id);
$stmt_delete->execute();

// Obtener equipos con sus jugadores
$sql_equipos = "
  SELECT e.id AS equipo_id, j.id AS jugador_id, j.nombre, j.apellido 
  FROM equipos e
  JOIN equipos_jugadores ej ON e.id = ej.equipo_id
  JOIN jugadores j ON ej.jugador_id = j.id
  WHERE e.torneo_id = ?
  ORDER BY e.id, ej.orden";

$stmt_equipos = $conn->prepare($sql_equipos);
$stmt_equipos->bind_param("i", $torneo_id);
$stmt_equipos->execute();
$result_equipos = $stmt_equipos->get_result();

$equipos = [];

while ($row = $result_equipos->fetch_assoc()) {
    $equipo_id = $row['equipo_id'];

    if (!isset($equipos[$equipo_id])) {
        $equipos[$equipo_id] = ['id' => $equipo_id, 'jugadores' => []];
    }

    $equipos[$equipo_id]['jugadores'][] = [
        'id' => $row['jugador_id'],
        'nombre' => $row['nombre'],
        'apellido' => $row['apellido']
    ];
}

$equipos_list = array_values($equipos);

if (count($equipos_list) < 2) {
    echo json_encode(['error' => 'No hay suficientes equipos para generar partidos']);
    exit;
}

// Función para guardar un partido
function guardarPartido($conn, $torneo_id, $equipo1_id, $equipo2_id) {
    // Insertar partido
    $stmt_partido = $conn->prepare("INSERT INTO partidos (torneo_id) VALUES (?)");
    $stmt_partido->bind_param("i", $torneo_id);
    $stmt_partido->execute();
    $partido_id = $stmt_partido->insert_id;

    // Guardar equipos
    if ($equipo1_id) {
        $stmt_pe1 = $conn->prepare("INSERT INTO partidos_equipos (partido_id, equipo_id) VALUES (?, ?)");
        $stmt_pe1->bind_param("ii", $partido_id, $equipo1_id);
        $stmt_pe1->execute();
    }

    if ($equipo2_id) {
        $stmt_pe2 = $conn->prepare("INSERT INTO partidos_equipos (partido_id, equipo_id) VALUES (?, ?)");
        $stmt_pe2->bind_param("ii", $partido_id, $equipo2_id);
        $stmt_pe2->execute();
    }

    return $partido_id;
}

// Generar partidos según formato
$partidos = [];

switch ($formato) {
    case 'liguilla':
        for ($i = 0; $i < count($equipos_list); $i++) {
            for ($j = $i + 1; $j < count($equipos_list); $j++) {
                $partido_id = guardarPartido($conn, $torneo_id, $equipos_list[$i]['id'], $equipos_list[$j]['id']);
                $partidos[] = [
                    'id' => $partido_id,
                    'equipo1' => $equipos_list[$i],
                    'equipo2' => $equipos_list[$j]
                ];
            }
        }
        break;

    case 'eliminatoria':
        if (count($equipos_list) !== 8) {
            echo json_encode(['error' => 'Para eliminatoria se necesitan exactamente 8 equipos']);
            exit;
        }

        $grupos = array_chunk($equipos_list, 2);

        // Octavos
        // Octavos
        if ($grupos[0][0] && $grupos[0][1]) {
            guardarPartido($conn, $torneo_id, $grupos[0][0]['id'], $grupos[0][1]['id']);
            $partidos[] = ['equipo1' => $grupos[0][0], 'equipo2' => $grupos[0][1]];
        }
        // Octavos
        if ($grupos[1][0] && $grupos[1][1]) {
            guardarPartido($conn, $torneo_id, $grupos[1][0]['id'], $grupos[1][1]['id']);
            $partidos[] = ['equipo1' => $grupos[1][0], 'equipo2' => $grupos[1][1]];
        }
        // Octavos
        if ($grupos[2][0] && $grupos[2][1]) {
            guardarPartido($conn, $torneo_id, $grupos[2][0]['id'], $grupos[2][1]['id']);
            $partidos[] = ['equipo1' => $grupos[2][0], 'equipo2' => $grupos[2][1]];
        }
                // Octavos
        if ($grupos[3][0] && $grupos[3][1]) {
            guardarPartido($conn, $torneo_id, $grupos[3][0]['id'], $grupos[3][1]['id']);
            $partidos[] = ['equipo1' => $grupos[3][0], 'equipo2' => $grupos[3][1]];
        }

        // Semifinales y final (vacío por ahora)
        $partidos[] = ['equipo1' => null, 'equipo2' => null, 'tipo' => 'semifinal'];
        $partidos[] = ['equipo1' => null, 'equipo2' => null, 'tipo' => 'final'];

        // Guardar en BD
        foreach ($partidos as $p) {
    if ($p['equipo1'] && $p['equipo2']) {
        guardarPartido($conn, $torneo_id, $p['equipo1']['id'], $p['equipo2']['id']);
    }
    echo "<pre>";
print_r($p);
echo "</pre>";
}

        break;

    case 'round-robin':
        for ($i = 0; $i < count($equipos_list); $i++) {
            for ($j = $i + 1; $j < count($equipos_list); $j++) {
                $partido_id = guardarPartido($conn, $torneo_id, $equipos_list[$i]['id'], $equipos_list[$j]['id']);
                $partidos[] = [
                    'id' => $partido_id,
                    'equipo1' => $equipos_list[$i],
                    'equipo2' => $equipos_list[$j]
                ];
                
            }
        }
        break;

    default:
        echo json_encode(['error' => 'Formato no soportado']);
        exit;
}

echo json_encode([
    'success' => true,
    'formato' => $formato,
    'partidos' => $partidos
]);
?>