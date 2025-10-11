
<?php
require_once 'config.php';

$items_per_page = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

function validate_input($data) {
    return htmlspecialchars(trim($data));
}

try {
    // Añadir usuario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
        $nombre = validate_input($_POST['nombre']);
        $email = validate_input($_POST['email']);
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

        if (empty($nombre) || empty($email) || empty($contraseña)) {
            throw new Exception("Datos de entrada inválidos");
        }

        $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $contraseña);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Usuario registrado con éxito.";
    }

    // Actualizar usuario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
        $id = (int)$_POST['id'];
        $nombre = validate_input($_POST['nombre']);
        $email = validate_input($_POST['email']);
        $contraseña = !empty($_POST['contraseña']) ? password_hash($_POST['contraseña'], PASSWORD_DEFAULT) : null;

        if (empty($nombre) || empty($email) || $id <= 0) {
            throw new Exception("Datos de entrada inválidos");
        }

        if ($contraseña) {
            $sql = "UPDATE usuarios SET nombre = ?, email = ?, contraseña = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $nombre, $email, $contraseña, $id);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $nombre, $email, $id);
        }
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Usuario actualizado con éxito.";
    }

    // Eliminar usuario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            throw new Exception("ID de usuario inválido");
        }

        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Usuario eliminado con éxito.";
    }

    // Buscar usuarios
    $search = isset($_GET['search']) ? validate_input($_GET['search']) : '';
    $sql = "SELECT id, nombre, email, fecha_registro 
            FROM usuarios 
            WHERE nombre LIKE ? OR email LIKE ? 
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    $search_param = "%$search%";
    mysqli_stmt_bind_param($stmt, "ssii", $search_param, $search_param, $items_per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Contar total para paginación
    $count_sql = "SELECT COUNT(*) as total FROM usuarios WHERE nombre LIKE ? OR email LIKE ?";
    $count_stmt = mysqli_prepare($conn, $count_sql);
    mysqli_stmt_bind_param($count_stmt, "ss", $search_param, $search_param);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $total_items = mysqli_fetch_assoc($count_result)['total'];
    $total_pages = ceil($total_items / $items_per_page);

    echo "<h2>Lista de Usuarios</h2>";
    echo "<form method='get'><input type='text' name='search' value='$search' placeholder='Buscar por nombre o email'><input type='submit' value='Buscar'></form>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha de Registro</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['nombre']}</td><td>{$row['email']}</td><td>{$row['fecha_registro']}</td></tr>";
    }
    echo "</table>";

    // Paginación
    echo "<div>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i&search=$search'>$i</a> ";
    }
    echo "</div>";

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($count_stmt);
} catch (mysqli_sql_exception $e) {
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error: $error_msg";
} catch (Exception $e) {
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error inesperado: $error_msg";
}

mysqli_close($conn);
?>

<form method="post">
    <h3>Registrar Usuario</h3>
    <div><label>Nombre</label><input type="text" name="nombre" required></div>
    <div><label>Email</label><input type="email" name="email" required></div>
    <div><label>Contraseña</label><input type="password" name="contraseña" required></div>
    <input type="submit" name="add_user" value="Registrar Usuario">
</form>

<form method="post">
    <h3>Actualizar Usuario</h3>
    <div><label>ID</label><input type="number" name="id" required></div>
    <div><label>Nombre</label><input type="text" name="nombre" required></div>
    <div><label>Email</label><input type="email" name="email" required></div>
    <div><label>Nueva Contraseña (opcional)</label><input type="password" name="contraseña"></div>
    <input type="submit" name="update_user" value="Actualizar Usuario">
</form>

<form method="post">
    <h3>Eliminar Usuario</h3>
    <div><label>ID</label><input type="number" name="id" required></div>
    <input type="submit" name="delete_user" value="Eliminar Usuario">
</form>
