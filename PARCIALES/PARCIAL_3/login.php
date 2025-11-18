<?php
session_start();

$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
$contrasena = filter_input(INPUT_POST, 'contrasena', FILTER_SANITIZE_STRING);


    if ($usuario === "estu25" && $contrasena === "DS7TP") {
        $_SESSION['usuario'] = $usuario;
        header("Location: dasboardestudiante.php");
        exit();
} 
    else  if ($usuario === "profe25" && $contrasena === "DS7EP") {
        $_SESSION['usuario'] = $usuario;
        header("Location: dasboardprofesor.php");
        exit();
    }
     else {
        $error = "Usuario o contraseña incorrectos";
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="usuario">Usuario:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>