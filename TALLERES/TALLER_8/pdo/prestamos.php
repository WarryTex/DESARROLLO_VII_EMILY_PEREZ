<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Préstamos</title>
<style>body{font-family: Arial; margin: 40px;} table, th, td{border:1px solid #ccc; padding:8px; border-collapse:collapse;} th{background:#f0f0f0;}</style>
</head>
<body>
<h1>Gestión de Préstamos</h1>
<a href="index.php">← Volver</a><hr>

<!-- Registrar préstamo -->
<h2>Realizar Préstamo</h2>
<form method="post">
    <select name="libro_id" required>
        <option value="">-- Seleccionar Libro --</option>
        <?php
        $stmt = $pdo->query("SELECT id, titulo, cantidad_disponible FROM libros WHERE cantidad_disponible > 0 ORDER BY titulo");
        while ($l = $stmt->fetch()) {
            echo "<option value='".$l['id']."'>".htmlspecialchars($l['titulo'])." (".$l['cantidad_disponible']." disp.)</option>";
        }
        ?>
    </select>

    <select name="usuario_id" required>
        <option value="">-- Seleccionar Usuario --</option>
        <?php
        $stmt = $pdo->query("SELECT id, nombre FROM usuarios ORDER BY nombre");
        while ($u = $stmt->fetch()) {
            echo "<option value='".$u['id']."'>".htmlspecialchars($u['nombre'])."</option>";
        }
        ?>
    </select>

    <button type="submit" name="prestar">Prestar Libro</button>
</form>

<?php
// Registrar préstamo con transacción
if (isset($_POST['prestar'])) {
    $libro_id = $_POST['libro_id'];
    $usuario_id = $_POST['usuario_id'];

    try {
        $pdo->beginTransaction();

        // Verificar disponibilidad
        $stmt = $pdo->prepare("SELECT cantidad_disponible FROM libros WHERE id = ? FOR UPDATE");
        $stmt->execute([$libro_id]);
        $disp = $stmt->fetchColumn();

        if ($disp <= 0) throw new Exception("Libro no disponible");

        // Registrar préstamo
        $stmt = $pdo->prepare("INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo) VALUES (?, ?, CURDATE())");
        $stmt->execute([$libro_id, $usuario_id]);

        // Reducir cantidad
        $pdo->prepare("UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ?")->execute([$libro_id]);

        $pdo->commit();
        echo "<p style='color:green;'>Préstamo registrado correctamente.</p>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color:red;'>Error: ".$e->getMessage()."</p>";
    }
}

// Devolver libro
if (isset($_GET['devolver'])) {
    $id = (int)$_GET['devolver'];
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT libro_id FROM prestamos WHERE id = ? AND devuelto = 0");
        $stmt->execute([$id]);
        $libro_id = $stmt->fetchColumn();

        $pdo->prepare("UPDATE prestamos SET devuelto = 1, fecha_devolucion = CURDATE() WHERE id = ?")->execute([$id]);
        $pdo->prepare("UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?")->execute([$libro_id]);

        $pdo->commit();
        echo "<p style='color:green;'>Libro devuelto.</p>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color:red;'>Error al devolver.</p>";
    }
}

// Listar préstamos activos
echo "<h2>Préstamos Activos</h2>";
$stmt = $pdo->query("
    SELECT p.id, l.titulo, u.nombre, p.fecha_prestamo 
    FROM prestamos p 
    JOIN libros l ON p.libro_id = l.id 
    JOIN usuarios u ON p.usuario_id = u.id 
    WHERE p.devuelto = 0 
    ORDER BY p.fecha_prestamo DESC
");
?>
<table>
    <tr><th>ID</th><th>Libro</th><th>Usuario</th><th>Fecha Préstamo</th><th>Acción</th></tr>
    <?php while ($p = $stmt->fetch()): ?>
    <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo htmlspecialchars($p['titulo']); ?></td>
        <td><?php echo htmlspecialchars($p['nombre']); ?></td>
        <td><?php echo $p['fecha_prestamo']; ?></td>
        <td><a href="?devolver=<?php echo $p['id']; ?>" onclick="return confirm('¿Marcar como devuelto?')">Devolver</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<h2>Historial Completo</h2>
<?php
$stmt = $pdo->query("
    SELECT p.*, l.titulo, u.nombre, u.email 
    FROM prestamos p 
    JOIN libros l ON p.libro_id = l.id 
    JOIN usuarios u ON p.usuario_id = u.id 
    ORDER BY p.fecha_prestamo DESC LIMIT 50
");
while ($p = $stmt->fetch()) {
    $estado = $p['devuelto'] ? "Devuelto el ".$p['fecha_devolucion'] : "Activo";
    echo "<p><strong>{$p['titulo']}</strong> → {$p['nombre']} ({$p['email']}) - Préstamo: {$p['fecha_prestamo']} - <em>$estado</em></p>";
}
?>
</body>
</html>