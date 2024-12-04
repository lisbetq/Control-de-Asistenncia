<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';
session_start();

// Procesar el formulario de registro
if (isset($_POST['register'])) {
    $userType = $_POST['userType'];
    $firstName = mysqli_real_escape_string($conexion, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conexion, $_POST['lastName']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Comprobar si el correo electrónico ya existe
    if ($userType == "Administrator") {
        $checkEmailQuery = "SELECT * FROM tbladmin WHERE emailAddress='$email'";
    } elseif ($userType == "Lecture") {
        $checkEmailQuery = "SELECT * FROM tbllecture WHERE emailAddress='$email'";
    }
    $result = $conexion->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "<script>alert('Este correo electrónico ya está registrado. Por favor, usa otro.');</script>";
    } else {
        // Insertar el nuevo usuario según su rol
        if ($userType == "Administrator") {
            $insertQuery = "INSERT INTO tbladmin (firstName, lastName, emailAddress, password) VALUES ('$firstName', '$lastName', '$email', '$password')";
        } elseif ($userType == "Lecture") {
            $insertQuery = "INSERT INTO tbllecture (firstName, lastName, emailAddress, password, phoneNo, facultyCode, dateCreated) VALUES ('$firstName', '$lastName', '$email', '$password', '', '', NOW())";
        }

        if ($conexion->query($insertQuery) === TRUE) {
            echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error al registrar. Inténtalo de nuevo.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Estilo general del contenedor del formulario */
        body, html {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        .login-form h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .login-form select, 
        .login-form input[type="text"], 
        .login-form input[type="email"], 
        .login-form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
            font-size: 16px;
        }

        /* Cambiar el color del borde al enfocar */
        .login-form select:focus, 
        .login-form input[type="text"]:focus, 
        .login-form input[type="email"]:focus, 
        .login-form input[type="password"]:focus {
            border-color: #1565c0;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            background-color: #1565c0;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #0d47a1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Registro de Usuario</h1>
            <form method="post" action="">
                <select required name="userType">
                    <option value="">Seleccione su Rol</option>
                    <option value="Administrator">Administrador</option>
                    <option value="Lecture">Docente</option>
                    <option value="Lecture">Estudiante</option>
                    <option value="Lecture">Tutor</option>
                </select>
                <input type="text" name="firstName" placeholder="Nombre" required>
                <input type="text" name="lastName" placeholder="Apellido" required>
                <input type="email" name="email" placeholder="Correo Electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <input type="submit" class="btn-login" value="Registrar" name="register">
            </form>
        </div>
    </div>
</body>
</html>