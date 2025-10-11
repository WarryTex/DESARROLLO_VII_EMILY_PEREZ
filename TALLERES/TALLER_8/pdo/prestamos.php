
<?php
require_once 'config.php';

$items_per_page = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

function validate_input($data) {
    return htmlspecialchars(trim($data));
}

try {
    // Registrar préstamo
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_loan'])) {
        $usuario_id = (int)$_POST['usuario_id'];
        $libro_id = (int)$_POST['libro_id'];

        if ($usuario_id <= 0 || $libro_id <= 0) {
            throw new Exception("ID de usuario o libro inválido");
        }

        $pdo->beginTransaction();

        // Verificar disponibilidad
        $sql = "SELECT cantidad_disponible FROM libros WHERE id = :id FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $libro_id]);
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$libro || $libro['cantidad_disponible'] <= 0) {
            throw new Exception("Libro no disponible");
        }

        // Registrar préstamo
        $sql = "INSERT INTO prestamos (usuario_id, libro_id) VALUES (:usuario_id, :libro_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id, ':libro_id' => $libro_id]);

        // Actualizar cantidad disponible
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $libro_id]);

        $pdo->commit();
        echo "Préstamo registrado con éxito.";
    }

    // Registrar devolución
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_loan'])) {
        $prestamo_id = (int)$_POST['prestamo_id'];

        if ($prestamo_id <= 0) {
            throw new Exception("ID de préstamo inválido");
        }

        $pdo->beginTransaction();

        // Verificar estado del préstamo
        $sql = "SELECT libro_id, estado FROM prestamos WHERE id = :id FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $prestamo_id]);
        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prestamo || $prestamo['estado'] == 'devuelto') {
            throw new Exception("Préstamo inválido o ya devuelto");
        }

        // Actualizar estado del préstamo
        $sql = "UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $prestamo_id]);

        // Restaurar cantidad disponible
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $prestamo['libro_id']]);

        $pdo->commit();
        echo "Devolución registrada con éxito.";
    }

    // Listar préstamos activos
    $sql = "SELECT p.id, u.nombre, l.titulo, p.fecha_prestamo 
            FROM prestamos p 
            INNER JOIN usuarios u ON p.usuario_id = u.id 
            INNER JOIN libros l ON p.libro_id = l.id 
            WHERE p.estado = 'activo' 
            LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar total para paginación
    $count_sql = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute();
    $total_items = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_items / $items_per_page);

    echo "<h2>Préstamos Activos</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Usuario</th><th>Libro</th><th>Fecha Préstamo</th></tr>";
    foreach ($result as $row) {
        echo "<tr><td>{$row['id']}</td><td>{$row['nombre']}</td><td>{$row['titulo']}</td><td>{$row['fecha_prestamo']}</td></tr>";
    }
    echo "</table>";

    // Paginación
    echo "<div>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
    echo "</div>";

    // Historial de préstamos por usuario
    if (isset($_GET['user_id'])) {
        $user_id = (int)$_GET['user_id'];
        if ($user_id <= 0) {
            throw new Exception("ID de usuario inválido");
        }

        $sql = "SELECT p.id, l.titulo, p.fecha_prestamo, p.fecha_devolucion, p.estado 
                FROM prestamos p 
                INNER JOIN libros l ON p.libro_id = l.id 
                WHERE p.usuario_id = :user_id 
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Historial de Préstamos</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Libro</th><th>Fecha Préstamo</th><th>Fecha Devolución</th><th>Estado</th></tr>";
        foreach ($result as $row) {
            echo "<tr><td>{$row['id']}</td><td>{$row['titulo']}</td><td>{$row['fecha_prestamo']}</td><td>" . ($row['fecha_devolucion'] ?? 'N/A') . "</td><td>{$row['estado']}</td></tr>";
        }
        echo "</table>";
    }

} catch (PDOException $e) {
    $pdo->rollBack();
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error: $error_msg";
} catch (Exception $e) {
    $pdo->rollBack();
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error inesperado: $error_msg";
}

$pdo = null;
?>

<form method="post">
    <h3>Registrar Préstamo</h3>
    <div><label>ID Usuario</label><input type="number" name="usuario_id" required></div>
    <div><label>ID Libro</label><input type="number" name="libro_id" required></div>
    <input type="submit" name="add_loan" value="Registrar Préstamo">
</form>

<form method="post">
    <h3>Registrar Devolución</h3>
    <div><label>ID Préstamo</label><input type="number" name="prestamo_id" required></div>
    <input type="submit" name="return_loan" value="Registrar Devolución">
</form>

<form method="get">
    <h3>Ver Historial de Préstamos</h3>
    <div><label>ID Usuario</label><input type="number" name="user_id" required></div>
    <input type="submit" value="Ver Historial">
</form>
