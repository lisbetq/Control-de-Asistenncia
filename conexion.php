<?php
// conexion.php
$servername = "localhost";
$username = "root";  // Usuario de MySQL (ajústalo si es diferente)
$password = "";  // Contraseña de MySQL (ajústala si es diferente)
$dbname = "attendancemsystem";  // Nombre de la base de datos

// Crear la conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
