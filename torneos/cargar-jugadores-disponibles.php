<?php
require '../db/db.php';

$torneo_id = isset($_GET['torneo_id']) ? intval($_GET['torneo_id']) : 0;

$sql = "
  SELECT j.id, j.nombre, j.apellido 
  FROM jugadores j
  LEFT JOIN torneos_jugadores tj ON j.id = tj.jugador_id AND tj.torneo_id = ?
  WHERE tj.jugador_id IS NULL
  ORDER BY j.apellido ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $torneo_id);
$stmt->execute();
$result = $stmt->get_result();

$jugadores = [];

while ($row = $result->fetch_assoc()) {
    $jugadores[] = $row;
}

echo json_encode($jugadores);
?>