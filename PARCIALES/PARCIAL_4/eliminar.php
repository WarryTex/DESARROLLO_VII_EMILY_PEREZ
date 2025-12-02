<?php

// Borrar producto por ID
if(isset($_POST['borrar'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM productos WHERE id=$id";
    mysqli_query($conn, $sql);
    echo "Eliminado con exito";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
</head>
<body>
<div class="container">
    <h1>Eliminar productos</h1>
    
    <h3>Borrar</h3>
    <form method="post">
        ID: <input type="number" name="id"><br>
        <input type="submit" name="borrar" value="Borrar">
    </form>
    
    <br><a href="index.php">Volver</a>
</div>
</body>
</html>