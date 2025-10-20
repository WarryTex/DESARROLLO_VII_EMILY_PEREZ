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
    $nombre = limpiar($_POST['nombre']);
    $email = limpiar($_POST['email']);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES ('$nombre', '$email', '$pass')";
    mysqli_query($conn, $sql);
    echo "Usuario agregado!";
}

// ACTUALIZAR
if(isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = limpiar($_POST['nombre']);
    $email = limpiar($_POST['email']);
    $pass = "";
    if($_POST['pass'] != "") $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
    if($pass != "") {
        $sql = "UPDATE usuarios SET nombre='$nombre', email='$email', contraseña='$pass' WHERE id=$id";
    } else {
        $sql = "UPDATE usuarios SET nombre='$nombre', email='$email' WHERE id=$id";
    }
    mysqli_query($conn, $sql);
    echo "Usuario actualizado!";
}

// BORRAR
if(isset($_POST['borrar'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM usuarios WHERE id=$id";
    mysqli_query($conn, $sql);
    echo "Usuario borrado!";
}

// BUSCAR
$buscar = "";
if(isset($_GET['buscar'])) $buscar = $_GET['buscar'];
$sql = "SELECT id, nombre, email, fecha_registro FROM usuarios WHERE nombre LIKE '%$buscar%' OR email LIKE '%$buscar%' LIMIT 5 OFFSET $inicio";
$resultado = mysqli_query($conn, $sql);

$count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM usuarios WHERE nombre LIKE '%$buscar%' OR email LIKE '%$buscar%'"));
$paginas = ceil($count['total'] / 5);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Usuarios</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h1>USUARIOS</h1>
    
    <form method="get" class="buscador">
        <input type="text" name="buscar" value="<?php echo $buscar; ?>" placeholder="Buscar">
        <input type="submit" value="Buscar">
    </form>
    
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Email</th><th>Fecha</th>
        </tr>
        <?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
        <tr>
            <td><?php echo $fila['id']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['email']; ?></td>
            <td><?php echo $fila['fecha_registro']; ?></td>
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
        Nombre: <input type="text" name="nombre"><br>
        Email: <input type="email" name="email"><br>
        Contraseña: <input type="password" name="pass"><br>
        <input type="submit" name="agregar" value="Agregar">
    </form>
    
    <h3>Actualizar</h3>
    <form method="post">
        ID: <input type="number" name="id"><br>
        Nombre: <input type="text" name="nombre"><br>
        Email: <input type="email" name="email"><br>
        Nueva Pass: <input type="password" name="pass"><br>
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