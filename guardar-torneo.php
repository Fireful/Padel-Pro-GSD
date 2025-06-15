<?php
// guardar-torneo.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	echo "<pre>";
    print_r($_POST); // Verifica que llegan todos los campos
    echo "</pre>";
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $fecha_inicio = $conn->real_escape_string($_POST['fecha-inicio']);
    $fecha_fin = $conn->real_escape_string($_POST['fecha-fin']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $formato = $conn->real_escape_string($_POST['formato']);
    $max_participantes = intval($_POST['max-participantes']);

    if (!in_array($categoria, ['masculino', 'femenino', 'mixto']) ||
        !in_array($formato, ['liguilla', 'eliminatoria', 'round-robin']) ||
        $max_participantes < 2) {
        die("Datos inválidos.");
    }

    $stmt = $conn->prepare("INSERT INTO torneos (nombre, fecha_inicio, fecha_fin, categoria, formato, max_participantes)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $fecha_inicio, $fecha_fin, $categoria, $formato, $max_participantes);

    if ($stmt->execute()) {
        echo "<p>Torneo guardado correctamente.</p>";
        header("Location: ../index.php");
        exit;
    } else {
        echo "Error al guardar el torneo: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>