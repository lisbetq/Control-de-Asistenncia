<?php
// Habilitar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión
include 'conexion.php';
session_start();

// Verificar si el token está presente en la URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    echo "Token recibido: $token<br>";
} else {
    die("Token no especificado.");
}

// Verificar conexión a la base de datos
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    echo "Conexión a la base de datos establecida<br>";
}

// Ejecutar consulta para verificar si el token existe en alguna de las tablas
$query = "
    SELECT Id, emailAddress AS email FROM tbladmin WHERE reset_token='$token'
    UNION 
    SELECT Id, emailAddress AS email FROM tbllecture WHERE reset_token='$token'
    UNION 
    SELECT Id, email FROM tblstudents WHERE reset_token='$token'
";

$result = $conexion->query($query);

// Verificar si la consulta fue exitosa
if ($result) {
    echo "Consulta ejecutada con éxito<br>";
} else {
    echo "Error en la consulta: " . $conexion->error;
    exit;
}

// Verificar si el token existe en la base de datos
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<p>Usuario encontrado con el correo: " . $user['email'] . "</p>";

    // Formulario para restablecer la contraseña
    if (isset($_POST['submit'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            // Encriptar la nueva contraseña
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la tabla correspondiente
            $update_query_admin = "UPDATE tbladmin SET password='$hashed_password', reset_token=NULL WHERE reset_token='$token'";
            $update_query_lecture = "UPDATE tbllecture SET password='$hashed_password', reset_token=NULL WHERE reset_token='$token'";
            $update_query_student = "UPDATE tblstudents SET password='$hashed_password', reset_token=NULL WHERE reset_token='$token'";

            $conexion->query($update_query_admin);
            $conexion->query($update_query_lecture);
            $conexion->query($update_query_student);

            echo "<p>Contraseña actualizada con éxito.</p>";
        } else {
            echo "<p>Las contraseñas no coinciden.</p>";
        }
    }
} else {
    echo "<p>Token no válido o usuario no encontrado.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h2>Restablecer Contraseña</h2>
    <?php if ($result && $result->num_rows > 0): ?>
        <form method="POST" action="">
            <label>Nueva Contraseña:</label>
            <input type="password" name="new_password" required><br>
            <label>Confirmar Contraseña:</label>
            <input type="password" name="confirm_password" required><br>
            <button type="submit" name="submit">Restablecer Contraseña</button>
        </form>
    <?php endif; ?>
</body>
</html>
