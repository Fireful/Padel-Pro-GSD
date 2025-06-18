<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $mano_dominante = $conn->real_escape_string($_POST['mano_dominante']);
    $nivel = $conn->real_escape_string($_POST['nivel']);


    if (!in_array($categoria, ['masculino', 'femenino', 'mixto']) || empty($nombre) || empty($apellido)) {
        die("Datos inválidos.");
    }

    $stmt = $conn->prepare("INSERT INTO jugadores (nombre, apellido, categoria, mano_dominante, nivel) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellido, $categoria, $mano_dominante, $nivel);

    if ($stmt->execute()) {
        header("Location: lista-jugadores.php");
        exit;
    } else {
        echo "Error al guardar el jugador.";
    }
}
?>