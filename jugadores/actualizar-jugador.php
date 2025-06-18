<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $mano_dominante = $conn->real_escape_string($_POST['mano_dominante']);
    $nivel = $conn->real_escape_string($_POST['nivel']);

    if (!in_array($categoria, ['masculino', 'femenino', 'mixto']) || empty($nombre) || empty($apellido) || (!in_array($mano_dominante, ['diestra', 'zurda'])) || (!in_array($nivel, ['principiante', 'intermedio', 'avanzado']))) {
        die("Datos inválidos.");
    }

    $stmt = $conn->prepare("UPDATE jugadores SET nombre=?, apellido=?, mano_dominante=?, categoria=?, nivel=? WHERE id=?");
    $stmt->bind_param("sssssi", $nombre, $apellido, $mano_dominante, $categoria, $nivel, $id);

    if ($stmt->execute()) {
        header("Location: lista-jugadores.php");
        exit;
    } else {
        echo "Error al actualizar el jugador.";
    }
}
?>