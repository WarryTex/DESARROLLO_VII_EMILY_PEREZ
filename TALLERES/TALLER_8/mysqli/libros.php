
<?php
require_once 'config.php';

$items_per_page = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

function validate_input($data) {
    return htmlspecialchars(trim($data));
}

try {
    // Añadir libro
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
        $titulo = validate_input($_POST['titulo']);
        $autor = validate_input($_POST['autor']);
        $isbn = validate_input($_POST['isbn']);
        $anio = (int)$_POST['anio_publicacion'];
        $cantidad = (int)$_POST['cantidad_disponible'];

        if (empty($titulo) || empty($autor) || empty($isbn) || $anio <= 0 || $cantidad < 0) {
            throw new Exception("Datos de entrada inválidos");
        }

        $sql = "INSERT INTO libros (titulo, autor, isbn, anio_publicacion, cantidad_disponible) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssii", $titulo, $autor, $isbn, $anio, $cantidad);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Libro añadido con éxito.";
    }

    // Actualizar libro
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_book'])) {
        $id = (int)$_POST['id'];
        $titulo = validate_input($_POST['titulo']);
        $autor = validate_input($_POST['autor']);
        $isbn = validate_input($_POST['isbn']);
        $anio = (int)$_POST['anio_publicacion'];
        $cantidad = (int)$_POST['cantidad_disponible'];

        if (empty($titulo) || empty($autor) || empty($isbn) || $anio <= 0 || $cantidad < 0 || $id <= 0) {
            throw new Exception("Datos de entrada inválidos");
        }

        $sql = "UPDATE libros SET titulo = ?, autor = ?, isbn = ?, anio_publicacion = ?, cantidad_disponible = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssiii", $titulo, $autor, $isbn, $anio, $cantidad, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Libro actualizado con éxito.";
    }

    // Eliminar libro
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_book'])) {
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            throw new Exception("ID de libro inválido");
        }

        $sql = "DELETE FROM libros WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Libro eliminado con éxito.";
    }

    // Buscar libros
    $search = isset($_GET['search']) ? validate_input($_GET['search']) : '';
    $sql = "SELECT id, titulo, autor, isbn, anio_publicacion, cantidad_disponible 
            FROM libros 
            WHERE titulo LIKE ? OR autor LIKE ? OR isbn LIKE ? 
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    $search_param = "%$search%";
    mysqli_stmt_bind_param($stmt, "sssii", $search_param, $search_param, $search_param, $items_per_page, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Contar total para paginación
    $count_sql = "SELECT COUNT(*) as total FROM libros WHERE titulo LIKE ? OR autor LIKE ? OR isbn LIKE ?";
    $count_stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($count_stmt, "sss", $search_param, $search_param, $search_param);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $total_items = mysqli_fetch_assoc($count_result)['total'];
    $total_pages = ceil($total_items / $items_per_page);

    echo "<h2>Lista de Libros</h2>";
    echo "<form method='get'><input type='text' name='search' value='$search' placeholder='Buscar por título, autor o ISBN'><input type='submit' value='Buscar'></form>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Año</th><th>Cantidad</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['titulo']}</td><td>{$row['autor']}</td><td>{$row['isbn']}</td><td>{$row['anio_publicacion']}</td><td>{$row['cantidad_disponible']}</td></tr>";
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
    <h3>Añadir Libro</h3>
    <div><label>Título</label><input type="text" name="titulo" required></div>
    <div><label>Autor</label><input type="text" name="autor" required></div>
    <div><label>ISBN</label><input type="text" name="isbn" required></div>
    <div><label>Año de Publicación</label><input type="number" name="anio_publicacion" required></div>
    <div><label>Cantidad Disponible</label><input type="number" name="cantidad_disponible" required></div>
    <input type="submit" name="add_book" value="Añadir Libro">
</form>

<form method="post">
    <h3>Actualizar Libro</h3>
    <div><label>ID</label><input type="number" name="id" required></div>
    <div><label>Título</label><input type="text" name="titulo" required></div>
    <div><label>Autor</label><input type="text" name="autor" required></div>
    <div><label>ISBN</label><input type="text" name="isbn" required></div>
    <div><label>Año de Publicación</label><input type="number" name="anio_publicacion" required></div>
    <div><label>Cantidad Disponible</label><input type="number" name="cantidad_disponible" required></div>
    <input type="submit" name="update_book" value="Actualizar Libro">
</form>

<form method="post">
    <h3>Eliminar Libro</h3>
    <div><label>ID</label><input type="number" name="id" required></div>
    <input type="submit" name="delete_book" value="Eliminar Libro">
</form>
