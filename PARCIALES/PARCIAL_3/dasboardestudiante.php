<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dasboard estudiantil</title>
</head>
<body>
    <h2>Bienvenido,estudiante UTP <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p>Observa tu calificación</p>
    <a href="cerrar_sesion.php">Cerrar Sesión</a>

    <?$json = file_get_contents('lista_estudiantes.json');
     $estudiantes = json_decode($json, true);?>
     
</body>
</html>