<?php
include 'conexion.php'; //conexion a la base de datos//
session_start();
// Módulo: Gestión de Usuarios
// Verifica si el estudiante ha iniciado sesión
if (!isset($_SESSION['userId']) || $_SESSION['userType'] !== 'Estudiante') {
    header('Location: index.php');
    exit();
}

// Obtiene información del estudiante
$userId = $_SESSION['userId'];
$query = "SELECT firstName, lastName, email FROM tblstudent WHERE Id='$userId'";
$result = $conexion->query($query);
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Estudiante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #1565c0;
            color: #fff;
        }
        .header h1 {
            margin: 0;
        }
        .sidebar {
            width: 20%;
            background-color: #0d47a1;
            padding: 20px;
            color: #fff;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #1565c0;
        }
        .content {
            margin-left: 22%;
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            margin-top: 0;
            color: #333;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>HELLO, <?php echo $student['firstName']; ?>!</h1>
    <a href="logout.php" style="color: white;">Cerrar Sesión</a>
</div>
<!-- Módulo: Gestión de Navegación -->
<!-- Explicación: Barra lateral con enlaces a diferentes secciones del panel del estudiante -->
<div class="sidebar">
    <h3>Panel de Estudiante</h3>
    <a href="#perfil"><i class="fas fa-user"></i> Mi Perfil</a>
    <a href="#asistencia"><i class="fas fa-calendar-check"></i> Historial de Asistencia</a>
    <a href="#materias"><i class="fas fa-book"></i> Mis Materias</a>
    <a href="#notificaciones"><i class="fas fa-bell"></i> Notificaciones</a>
</div>

<div class="content">
    <div id="perfil" class="section">
        <h2>Mi Perfil</h2>
        <p><strong>Nombre:</strong> <?php echo $student['firstName'] . ' ' . $student['lastName']; ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo $student['email']; ?></p>
    </div>

    <div id="asistencia" class="section">
        <h2>Historial de Asistencia</h2>
        <?php
        $asistenciaQuery = "SELECT fecha, estado FROM asistencia WHERE student_id='$userId' ORDER BY fecha DESC";
        $asistenciaResult = $conexion->query($asistenciaQuery);

        if ($asistenciaResult->num_rows > 0) {
            echo "<table><tr><th>Fecha</th><th>Estado</th></tr>";
            while ($row = $asistenciaResult->fetch_assoc()) {
                echo "<tr><td>" . $row['fecha'] . "</td><td>" . $row['estado'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No se encontraron registros de asistencia.</p>";
        }
        ?>
    </div>

    <div id="materias" class="section">
        <h2>Mis Materias</h2>
        <?php
        $materiasQuery = "SELECT nombre_materia, profesor FROM materias WHERE student_id='$userId'";
        $materiasResult = $conexion->query($materiasQuery);

        if ($materiasResult->num_rows > 0) {
            echo "<ul>";
            while ($row = $materiasResult->fetch_assoc()) {
                echo "<li><strong>" . $row['nombre_materia'] . "</strong> - Profesor: " . $row['profesor'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se encontraron materias registradas.</p>";
        }
        ?>
    </div>

    <div id="notificaciones" class="section">
        <h2>Notificaciones</h2>
        <?php
        $notificacionesQuery = "SELECT mensaje, fecha FROM notificaciones WHERE student_id='$userId' ORDER BY fecha DESC";
        $notificacionesResult = $conexion->query($notificacionesQuery);

        if ($notificacionesResult->num_rows > 0) {
            echo "<ul>";
            while ($row = $notificacionesResult->fetch_assoc()) {
                echo "<li>" . $row['fecha'] . ": " . $row['mensaje'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No tienes notificaciones nuevas.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
