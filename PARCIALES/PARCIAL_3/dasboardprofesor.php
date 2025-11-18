<?php
session_start();

if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dasboard profesor</title>
</head>
<body>
    <h2>Bienvenido,profe25 <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p>Observa las calificaciones de tus estudiantes.</p>
    <a href="cerrar_sesion.php">Cerrar Sesión</a>

 <?php 
 
 echo $cargar = [
        [1, 'Emily Pérez', 'Ingeniería de Sistemas', ['Programación' => 95, 'Graficos' => 80, ]],
        [2, 'Victor Pérez', 'Ingeniería de Sistemas', ['Programación' => 0, 'Graficos' => 58, ]],
        [3, 'Victoria Pérez', 'Ingeniería de Sistemas', ['Programación' => 75, 'Graficos' => 88, ]],
        [4, 'estu25', 'Ingeniería de Sistemas', ['Programación' => 45, 'Graficos' => 100, ]],
    ];
    ?>

</body>
</html>