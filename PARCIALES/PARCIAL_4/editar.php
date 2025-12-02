<?php
// ACTUALIZAR POR ID

if(isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = limpiar($_POST['nombre']);
    $categoria = limpiar($_POST['categoria']);
    $precio = limpiar($_POST['precio']);
    $cantidad = $_POST['cantidad'];
    $fecha_registro = $_POST['fecha_registro'];
    
    $sql = "UPDATE productos SET nombre='$nombre', categoria='$categoria', precio='$precio', cantidad=$cantidad, fecha_registro=$fecha_registro WHERE id=$id";
    mysqli_query($conn, $sql);
    echo "Producto actualizado";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Producto</title>
</head>
<body>
<div class="container">
    <h1>Edita un producto</h1>
    
    <h3>Actualizar</h3>
    <form method="post">
        id: <input type="number" name="id"><br>
        Nombre: <input type="text" name="nombre"><br>
        Categoria: <input type="email" name="categoria"><br>
        Precio: <input type="number" name="precio"><br>
        Cantidad: <input type="number" name="cantidad"><br>
        Fecha de registro: <input type="date" name="fecha_registro"><br>
        <button type="submit">crear</button>
    </form>
        
    </form>
     <a href="index.php" class="btn">Volver</a>
</div>
</html>

