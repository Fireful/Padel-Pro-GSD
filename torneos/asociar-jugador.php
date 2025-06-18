<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $torneo_id = intval($_POST['torneo_id']);
    $jugador_id = intval($_POST['jugador_id']);

    if ($torneo_id <= 0 || $jugador_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    // Comprobar si ya existe la asociación
    $sql = "SELECT * FROM torneos_jugadores WHERE torneo_id = ? AND jugador_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $torneo_id, $jugador_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El jugador ya está asociado.']);
        exit;
    }

    // Insertar nueva asociación
    $sql = "INSERT INTO torneos_jugadores (torneo_id, jugador_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $torneo_id, $jugador_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al asociar jugador.']);
    }
}
?>