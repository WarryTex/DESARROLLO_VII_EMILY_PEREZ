
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

        $sql = "INSERT INTO libros (titulo, autor, isbn, anio_publicacion, cantidad_disponible) VALUES (:titulo, :autor, :isbn, :anio, :cantidad)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':isbn' => $isbn,
            ':anio' => $anio,
            ':cantidad' => $cantidad
        ]);
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

        $sql = "UPDATE libros SET titulo = :titulo, autor = :autor, isbn = :isbn, anio_publicacion = :anio, cantidad_disponible = :cantidad WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':isbn' => $isbn,
            ':anio' => $anio,
            ':cantidad' => $cantidad,
            ':id' => $id
        ]);
        echo "Libro actualizado con éxito.";
    }

    // Eliminar libro
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_book'])) {
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            throw new Exception("ID de libro inválido");
        }

        $sql = "DELETE FROM libros WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        echo "Libro eliminado con éxito.";
    }

    // Buscar libros
    $search = isset($_GET['search']) ? validate_input($_GET['search']) : '';
    $sql = "SELECT id, titulo, autor, isbn, anio_publicacion, cantidad_disponible 
            FROM libros 
            WHERE titulo LIKE :search OR autor LIKE :search OR isbn LIKE :search 
            LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar total para paginación
    $count_sql = "SELECT COUNT(*) as total FROM libros WHERE titulo LIKE :search OR autor LIKE :search OR isbn LIKE :search";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $count_stmt->execute();
    $total_items = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_items / $items_per_page);

    echo "<h2>Lista de Libros</h2>";
    echo "<form method='get'><input type='text' name='search' value='$search' placeholder='Buscar por título, autor o ISBN'><input type='submit' value='Buscar'></form>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Año</th><th>Cantidad</th></tr>";
    foreach ($result as $row) {
        echo "<tr><td>{$row['id']}</td><td>{$row['titulo']}</td><td>{$row['autor']}</td><td>{$row['isbn']}</td><td>{$row['anio_publicacion']}</td><td>{$row['cantidad_disponible']}</td></tr>";
    }
    echo "</table>";

    // Paginación
    echo "<div>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i&search=$search'>$i</a> ";
    }
    echo "</div>";

} catch (PDOException $e) {
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error: $error_msg";
} catch (Exception $e) {
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error inesperado: $error_msg";
}

$pdo = null;
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
