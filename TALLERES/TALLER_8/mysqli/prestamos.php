<?php
require_once 'config.php';

$pagina = 1;
if(isset($_GET['pagina'])) $pagina = $_GET['pagina'];
$inicio = ($pagina - 1) * 5;

// PRESTAR
if(isset($_POST['prestar'])) {
    $usuario = $_POST['usuario'];
    $libro = $_POST['libro'];
    
    $sql = "SELECT cantidad_disponible FROM libros WHERE id=$libro";
    $check = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    
    if($check['cantidad_disponible'] > 0) {
        $sql = "INSERT INTO prestamos (usuario_id, libro_id) VALUES ($usuario, $libro)";
        mysqli_query($conn, $sql);
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id=$libro";
        mysqli_query($conn, $sql);
        echo "Préstamo OK!";
    } else {
        echo "Sin libros!";
    }
}

// DEVOLVER
if(isset($_POST['devolver'])) {
    $prestamo = $_POST['prestamo'];
    
    $sql = "SELECT libro_id FROM prestamos WHERE id=$prestamo AND estado='activo'";
    $check = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    
    if($check) {
        $sql = "UPDATE prestamos SET estado='devuelto', fecha_devolucion=NOW() WHERE id=$prestamo";
        mysqli_query($conn, $sql);
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id=" . $check['libro_id'];
        mysqli_query($conn, $sql);
        echo "Devolución OK!";
    } else {
        echo "Error!";
    }
}

// MOSTRAR
$sql = "SELECT p.id, u.nombre, l.titulo, p.fecha_prestamo, p.fecha_devolucion, p.estado 
        FROM prestamos p 
        INNER JOIN usuarios u ON p.usuario_id = u.id 
        INNER JOIN libros l ON p.libro_id = l.id 
        LIMIT 5 OFFSET $inicio";
$resultado = mysqli_query($conn, $sql);

$count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM prestamos"));
$paginas = ceil($count['total'] / 5);

// HISTORIAL
if(isset($_GET['usuario'])) {
    $usuario = $_GET['usuario'];
    $sql = "SELECT p.id, l.titulo, p.fecha_prestamo, p.fecha_devolucion, p.estado 
            FROM prestamos p 
            INNER JOIN libros l ON p.libro_id = l.id 
            WHERE p.usuario_id = $usuario 
            LIMIT 5 OFFSET $inicio";
    $historial = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Préstamos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h1>PRÉSTAMOS</h1>
    
    <table>
        <tr>
            <th>ID</th><th>Usuario</th><th>Libro</th><th>Fecha Prestamo</th><th>Fecha Devolución</th><th>Estado</th>
        </tr>
        <?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['titulo']; ?></td>
            <td><?php echo $fila['fecha_prestamo']; ?></td>
            <td><?php echo $fila['fecha_devolucion'] ?? 'N/A'; ?></td>
            <td><?php echo $fila['estado']; ?></td>
        </tr>
        <?php } ?>
    </table>
    
    <div class="paginacion">
        <?php for($i=1; $i<=$paginas; $i++) { ?>
            <a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php } ?>
    </div>
    
    <hr>
    
    <h3>Prestar</h3>
    <form method="post">
        Usuario ID: <input type="number" name="usuario"><br>
        Libro ID: <input type="number" name="libro"><br>
        <input type="submit" name="prestar" value="Prestar">
    </form>
    
    <h3>Devolver</h3>
    <form method="post">
        Préstamo ID: <input type="number" name="prestamo"><br>
        <input type="submit" name="devolver" value="Devolver">
    </form>
    
    <h3>Historial</h3>
    <form method="get">
        Usuario ID: <input type="number" name="usuario"><br>
        <input type="submit" value="Ver">
    </form>
    
    <?php if(isset($_GET['usuario'])) { ?>
        <h3>Historial <?php echo $usuario; ?></h3>
        <table>
            <tr>
                <th>ID</th><th>Libro</th><th>Fecha Prestamo</th><th>Fecha Devolución</th><th>Estado</th>
            </tr>
            <?php while($fila = mysqli_fetch_assoc($historial)) { ?>
            <tr>
                <td><?php echo $fila['id']; ?></td>
                <td><?php echo $fila['titulo']; ?></td>
                <td><?php echo $fila['fecha_prestamo']; ?></td>
                <td><?php echo $fila['fecha_devolucion'] ?? 'N/A'; ?></td>
                <td><?php echo $fila['estado']; ?></td>
            </tr>
            <?php } ?>
        </table>
    <?php } ?>
    
    <br><a href="index.php">Volver</a>
</div>
</body>
</html>
