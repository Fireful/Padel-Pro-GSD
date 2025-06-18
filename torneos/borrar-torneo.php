<?php
require '../db/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM torneos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Error al borrar el torneo.";
    }
} else {
    echo "ID inválido.";
}
?>