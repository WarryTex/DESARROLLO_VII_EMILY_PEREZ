<?php
require_once 'database.php';

function limpiar($texto) {
    return trim($texto);
}

// AGREGAR
if(isset($_POST['agregar'])) {
    $nombre = limpiar($_POST['nombre']);
    $categoria = limpiar($_POST['categoria']);
    $precio = limpiar($_POST['precio']);
    $cantidad =$_POST['cantidad'];
    $fecha_registro = $_POST['fecha_registro'];
    
    $sql = "INSERT INTO productos (nombre, categoria, precio, cantidad, fecha_registro) VALUES ('$nombre', '$categoria', '$precio', $cantidad, $fecha_registro)";
    mysqli_query($conn, $sql);
    echo "Agregado con exito!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crear_un_producto</title>
</head>
<body>
<div class="container">
    <h1>CREAR PRODUCTOS</h1>

<div class="Producto-form">
    <h2>Crear Nuevo producto</h2>
    <form action="index.php?action=create" method="post">

    <label>Nombre del producto:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Categoría:</label><br>
     <input type="text" name="categoria" required><br><br>

    <label>Precio:</label><br>
    <input type="number" name="precio" required><br><br>

    <label>Cantidad:</label><br>
    <input type="number" name="cantidad" required><br><br>

    <label>Fecha de registro:</label><br>
    <input type="date" name="fecha_registro"><br>

    <button type="submit">crear</button>
    </form>
    <br>
    <a href="index.php" class="btn">Volver</a>
</div>
</html>

