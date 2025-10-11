<?php
echo "Debug: productos.php está cargando";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config_sesion.php';


$productos = [
    ['id' => 1, 'nombre' => 'Camiseta', 'precio' => 19.99],
    ['id' => 2, 'nombre' => 'Pantalones', 'precio' => 29.99],
    ['id' => 3, 'nombre' => 'Zapatos', 'precio' => 49.99],
    ['id' => 4, 'nombre' => 'Chaqueta', 'precio' => 59.99],
    ['id' => 5, 'nombre' => 'Sombrero', 'precio' => 14.99]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda - Productos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .producto { border: 1px solid #ccc; padding: 10px; margin: 10px; display: inline-block; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Productos Disponibles</h1>
    <form action="productos.php" method="post">
        <label>Nombre de usuario: </label>
        <input type="text" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>">
        <input type="submit" value="Guardar Nombre">
    </form>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && !empty($_POST['username'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        setcookie('username', $username, time() + 86400, '/', '', false, true);
        echo "<p>Nombre guardado: " . htmlspecialchars($username) . "</p>";
    }
    ?>

    <h2>Lista de Productos</h2>
    <?php foreach ($productos as $producto): ?>
        <div class="producto">
            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
            <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
            <form action="agregar_al_carrito.php" method="post">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                <input type="number" name="cantidad" value="1" min="1">
                <input type="submit" value="Añadir al Carrito">
            </form>
        </div>
    <?php endforeach; ?>
    <p><a href="ver_carrito.php">Ver Carrito</a></p>
</body>
</html>