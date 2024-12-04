<?php
include 'conexion.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SISTEMA DE ASISTENCIA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Estilo general */
        body, html { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #0d47a1; font-family: Arial, sans-serif; }
        .container { display: flex; width: 85%; max-width: 1500px; height: 90vh; background: #f5f5f5; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 15px; overflow: hidden; }
        .login-form { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; background-color: #ffffff; }
        .login-form h1 { margin-bottom: 20px; color: #333; font-size: 28px; }
        .login-form input[type="email"], .login-form input[type="password"], .login-form select { width: 85%; padding: 12px; margin: 12px 0; border: 1px solid #ddd; border-radius: 30px; outline: none; text-align: center; }
        .login-form .btn-login { width: 85%; padding: 14px; margin: 15px 0; border: none; border-radius: 30px; background-color: #1565c0; color: #fff; cursor: pointer; transition: background-color 0.3s; }
        .login-form .btn-login:hover { background-color: #0d47a1; }
        .login-form .recover a { text-decoration: none; color: #1565c0; font-size: 14px; }
        .login-form .recover a:hover { color: #0d47a1; }
        .image-section { flex: 1.5; background: url('img/imagen_login.jpg') no-repeat center center/cover; position: relative; }
        .logo { position: absolute; top: 20px; right: 20px; width: 100px; height: auto; }
        .fingerprint-icon { margin-top: 20px; }
        .fingerprint-icon i { font-size: 2rem; color: #1565c0; }
    </style>
</head>
<body>

<div class="container">
    <!-- Sección de formulario de inicio de sesión -->
    <div class="login-form">
        <h1>Iniciar Sesión</h1>
        <form method="post" action="">
            <select required name="userType">
                <option value="">Seleccione su Rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Docente">Docente</option>
                <option value="Estudiante">Estudiante</option>
                <option value="Tutor">Tutor</option>
            </select>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <p class="recover"><a href="reset_password.php">Recuperar Contraseña</a></p>
            <input type="submit" class="btn-login" value="Ingresar" name="login" />
            <div class="fingerprint-icon">
                <i class="fas fa-fingerprint"></i>
            </div>
        </form>
    </div>

    <!-- Sección de imagen de fondo con el logo -->
    <div class="image-section">
        <img src="img/finesi.png" class="logo" alt="Logo">
    </div>
</div>

<?php
// Procesar el inicio de sesión
if (isset($_POST['login'])) {
    $userType = $_POST['userType'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encriptación de la contraseña

    // Consultar en la base de datos según el tipo de usuario seleccionado
    if ($userType == "Administrador") {
        $query = "SELECT * FROM tbladmin WHERE emailAddress = ? AND password = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $rs = $stmt->get_result();
        
        if ($rs && $rs->num_rows > 0) {
            $rows = $rs->fetch_assoc();
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['userType'] = 'Administrador';
            echo "<script>window.location = 'Admin/index.php';</script>"; // Redirigir al dashboard del administrador
        } else {
            echo "<script>alert('Usuario/Contraseña inválido');</script>";
        }
    } elseif ($userType == "Docente") {
        $query = "SELECT * FROM tbllecture WHERE emailAddress = ? AND password = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $rs = $stmt->get_result();
        
        if ($rs && $rs->num_rows > 0) {
            $rows = $rs->fetch_assoc();
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['userType'] = 'Docente';
            echo "<script>window.location = 'lecture/takeAttendance.php';</script>"; // Redirigir al dashboard del docente
        } else {
            echo "<script>alert('Usuario/Contraseña inválido');</script>";
        }
    } elseif ($userType == "Estudiante") {
        // Cambio aquí: Ahora consulta la tabla tblstudents
        $query = "SELECT * FROM tblstudent WHERE email = ? AND password = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $rs = $stmt->get_result();
        
        if ($rs && $rs->num_rows > 0) {
            $rows = $rs->fetch_assoc();
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['userType'] = 'Estudiante';
            echo "<script>window.location = 'student/dash_student.php';</script>"; // Redirigir al dashboard del estudiante
        } else {
            echo "<script>alert('Usuario/Contraseña inválido');</script>";
        }
    } elseif ($userType == "Tutor") {
        $query = "SELECT * FROM tbllecture WHERE emailAddress = ? AND password = ? AND facultyCode = 'TUTOR'";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $rs = $stmt->get_result();
            
        if ($rs && $rs->num_rows > 0) {
            $rows = $rs->fetch_assoc();
            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['userType'] = 'Tutor';
            echo "<script>window.location = 'Tutor/dash_tutor.php';</script>"; // Redirigir al dashboard del tutor
        } else {
            echo "<script>alert('Usuario/Contraseña inválido');</script>";
        }
    }    
        

    } else {
        echo "<script>alert('Tipo de usuario no válido');</script>";
    }

?>

</body>
</html>

