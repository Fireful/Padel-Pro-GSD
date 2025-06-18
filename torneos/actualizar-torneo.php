<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $formato = $conn->real_escape_string($_POST['formato']);
    $max_participantes = intval($_POST['max_participantes']);
    $jugadores = isset($_POST['jugadores']) ? array_map('intval', $_POST['jugadores']) : [];

    // Validaciones básicas
    if (!in_array($categoria, ['masculino', 'femenino', 'mixto']) || !in_array($formato, ['liguilla', 'eliminatoria', 'round-robin']) || $max_participantes < 2) {
        die("Datos inválidos.");
    }

    // Actualizar datos del torneo
    $stmt = $conn->prepare("UPDATE torneos SET nombre=?, categoria=?, formato=?, max_participantes=? WHERE id=?");
    $stmt->bind_param("ssssi", $nombre, $categoria, $formato, $max_participantes, $id);

    if (!$stmt->execute()) {
        die("Error al actualizar el torneo.");
    }

    // Borrar jugadores actuales
    $stmt = $conn->prepare("DELETE FROM torneos_jugadores WHERE torneo_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Insertar nuevos jugadores
    if (!empty($jugadores)) {
        $stmt = $conn->prepare("INSERT INTO torneos_jugadores (torneo_id, jugador_id) VALUES (?, ?)");
        foreach ($jugadores as $jugador_id) {
            $stmt->bind_param("ii", $id, $jugador_id);
            $stmt->execute();
        }
    }

    header("Location: ../index.php");
    exit;
}
?>