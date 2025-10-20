<?php
require_once 'config.php';

$pagina = 1;
if(isset($_GET['pagina'])) $pagina = $_GET['pagina'];
$inicio = ($pagina - 1) * 5;

function limpiar($texto) {
    return trim($texto);
}

// AGREGAR
if(isset($_POST['agregar'])) {
    $titulo = limpiar($_POST['titulo']);
    $autor = limpiar($_POST['autor']);
    $isbn = limpiar($_POST['isbn']);
    $anio = $_POST['anio'];
    $cantidad = $_POST['cantidad'];
    
    $sql = "INSERT INTO libros (titulo, autor, isbn, anio_publicacion, cantidad_disponible) VALUES ('$titulo', '$autor', '$isbn', $anio, $cantidad)";
    mysqli_query($conn, $sql);
    echo "Libro agregado OK!";
}

// ACTUALIZAR
if(isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $titulo = limpiar($_POST['titulo']);
    $autor = limpiar($_POST['autor']);
    $isbn = limpiar($_POST['isbn']);
    $anio = $_POST['anio'];
    $cantidad = $_POST['cantidad'];
    
    $sql = "UPDATE libros SET titulo='$titulo', autor='$autor', isbn='$isbn', anio_publicacion=$anio, cantidad_disponible=$cantidad WHERE id=$id";
    mysqli_query($conn, $sql);
    echo "Libro actualizado OK!";
}

// BORRAR
if(isset($_POST['borrar'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM libros WHERE id=$id";
    mysqli_query($conn, $sql);
    echo "Libro borrado OK!";
}

// BUSCAR
$buscar = "";
if(isset($_GET['buscar'])) $buscar = $_GET['buscar'];
$sql = "SELECT * FROM libros WHERE titulo LIKE '%$buscar%' OR autor LIKE '%$buscar%' LIMIT 5 OFFSET $inicio";
$resultado = mysqli_query($conn, $sql);

$count_sql = "SELECT COUNT(*) as total FROM libros WHERE titulo LIKE '%$buscar%' OR autor LIKE '%$buscar%'";
$total = mysqli_fetch_assoc(mysqli_query($conn, $count_sql))['total'];
$paginas = ceil($total / 5);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Libros</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h1>LIBROS</h1>
    
    <form method="get" class="buscador">
        <input type="text" name="buscar" value="<?php echo $buscar; ?>" placeholder="Buscar">
        <input type="submit" value="Buscar">
    </form>
    
    <table>
        <tr>
            <th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Año</th><th>Cant.</th>
        </tr>
        <?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['titulo']; ?></td>
            <td><?php echo $fila['autor']; ?></td>
            <td><?php echo $fila['isbn']; ?></td>
            <td><?php echo $fila['anio_publicacion']; ?></td>
            <td><?php echo $fila['cantidad_disponible']; ?></td>
        </tr>
        <?php } ?>
    </table>
    
    <div class="paginacion">
        <?php for($i=1; $i<=$paginas; $i++) { ?>
            <a href="?pagina=<?php echo $i; ?>&buscar=<?php echo $buscar; ?>"><?php echo $i; ?></a>
        <?php } ?>
    </div>
    
    <hr>
    
    <h3>Agregar</h3>
    <form method="post">
        Título: <input type="text" name="titulo"><br>
        Autor: <input type="text" name="autor"><br>
        ISBN: <input type="text" name="isbn"><br>
        Año: <input type="number" name="anio"><br>
        Cantidad: <input type="number" name="cantidad"><br>
        <input type="submit" name="agregar" value="Agregar">
    </form>
    
    <h3>Actualizar</h3>
    <form method="post">
        ID: <input type="number" name="id"><br>
        Título: <input type="text" name="titulo"><br>
        Autor: <input type="text" name="autor"><br>
        ISBN: <input type="text" name="isbn"><br>
        Año: <input type="number" name="anio"><br>
        Cantidad: <input type="number" name="cantidad"><br>
        <input type="submit" name="actualizar" value="Actualizar">
    </form>
    
    <h3>Borrar</h3>
    <form method="post">
        ID: <input type="number" name="id"><br>
        <input type="submit" name="borrar" value="Borrar">
    </form>
    
    <br><a href="index.php">Volver</a>
</div>
</body>
</html>