<?php
$host = "localhost";
$user = "root";      // Usuario de MySQL
$pass = "";          // Contraseña de MySQL
$db   = "veintidosrazonesparaaprobar_bd";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>