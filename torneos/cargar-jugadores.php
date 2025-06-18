<?php
require '../db/db.php';

$torneo_id = isset($_GET['torneo_id']) ? intval($_GET['torneo_id']) : 0;

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

echo json_encode($jugadores);
?>