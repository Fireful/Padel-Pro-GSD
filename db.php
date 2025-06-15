<?php
$host = "localhost";
$user = "root";         // Cambia si tu entorno lo requiere
$password = "";         // Cambia si usas contraseña
$database = "padel_pro_gsd";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>