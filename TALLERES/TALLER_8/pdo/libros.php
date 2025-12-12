<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Libros</title>
    <style>body{font-family: Arial; margin: 40px;} table, th, td{border:1px solid #ccc; padding:8px; border-collapse:collapse;} th{background:#f0f0f0;}</style>
</head>
<body>
    <h1>Gestionar Libros</h1>
    <a href="index.php">← Volver al inicio</a> | <a href="libros.php">Actualizar lista</a>
    <hr>

    <!-- Formulario añadir libro -->
    <h2>Añadir Nuevo Libro</h2>
    <form method="post">
        <input type="text" name="titulo" placeholder="Título" required> 
        <input type="text" name="autor" placeholder="Autor" required>
        <input type="text" name="isbn" placeholder="ISBN" required>
        <input type="number" name="año" placeholder="Año" min="1500" max="2100" required>
        <input type="number" name="cantidad" placeholder="Cantidad" min="0" required>
        <button type="submit" name="add">Añadir Libro</button>
    </form>

    <?php
    // Añadir libro
    if (isset($_POST['add'])) {
        $titulo = trim($_POST['titulo']);
        $autor = trim($_POST['autor']);
        $isbn = trim($_POST['isbn']);
        $año = $_POST['año'];
        $cantidad = $_POST['cantidad'];

        $stmt = $pdo->prepare("INSERT INTO libros (titulo, autor, isbn, año, cantidad_disponible) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $autor, $isbn, $año, $cantidad]);
        echo "<p style='color:green;'>Libro añadido correctamente.</p>";
    }

    // Eliminar libro
    if (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $pdo->prepare("DELETE FROM libros WHERE id = ?")->execute([$id]);
        echo "<p style='color:red;'>Libro eliminado.</p>";
    }

    // Búsqueda y paginación
    $search = $_GET['search'] ?? '';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    $where = $search ? "WHERE titulo LIKE :search OR autor LIKE :search OR isbn LIKE :search" : "";
    $like = "%$search%";

    $total = $pdo->prepare("SELECT COUNT(*) FROM libros $where");
    if ($search) $total->execute(['search' => $like]);
    else $total->execute();
    $totalRows = $total->fetchColumn();
    $totalPages = ceil($totalRows / $perPage);

    $stmt = $pdo->prepare("SELECT * FROM libros $where ORDER BY id DESC LIMIT $offset, $perPage");
    if ($search) $stmt->execute(['search' => $like]);
    else $stmt->execute();
    $libros = $stmt->fetchAll();
    ?>

    <h2>Lista de Libros (<?php echo $totalRows; ?> total)</h2>
    <form method="get">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por título, autor o ISBN">
        <button type="submit">Buscar</button>
        <?php if ($search): ?><a href="libros.php">Limpiar</a><?php endif; ?>
    </form><br>

    <table>
        <tr>
            <th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Año</th><th>Disponibles</th><th>Acciones</th>
        </tr>
        <?php foreach ($libros as $l): ?>
        <tr>
            <td><?php echo $l['id']; ?></td>
            <td><?php echo htmlspecialchars($l['titulo']); ?></td>
            <td><?php echo htmlspecialchars($l['autor']); ?></td>
            <td><?php echo $l['isbn']; ?></td>
            <td><?php echo $l['año']; ?></td>
            <td><?php echo $l['cantidad_disponible']; ?></td>
            <td>
                <a href="libros.php?delete=<?php echo $l['id']; ?>" 
                   onclick="return confirm('¿Eliminar este libro?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Paginación -->
    <div>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
               style="<?php if($i==$page) echo 'font-weight:bold;'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>