<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Gestionar Usuarios</title>
<style>body{font-family: Arial; margin: 40px;} table, th, td{border:1px solid #ccc; padding:8px; border-collapse:collapse;} th{background:#f0f0f0;}</style>
</head>
<body>
<h1>Gestionar Usuarios</h1>
<a href="index.php">← Volver</a><hr>

<h2>Registrar Usuario</h2>
<form method="post">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit" name="add">Registrar</button>
</form>

<?php
if (isset($_POST['add'])) {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $pass]);
        echo "<p style='color:green;'>Usuario registrado.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error: Email ya existe.</p>";
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);
    echo "<p style='color:red;'>Usuario eliminado.</p>";
}

// Búsqueda y paginación
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$where = $search ? "WHERE nombre LIKE :s OR email LIKE :s" : "";
$like = "%$search%";

$total = $pdo->prepare("SELECT COUNT(*) FROM usuarios $where");
if ($search) $total->execute(['s' => $like]); else $total->execute();
$totalRows = $total->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

$stmt = $pdo->prepare("SELECT id, nombre, email, creado_en FROM usuarios $where ORDER BY id DESC LIMIT $offset, $perPage");
if ($search) $stmt->execute(['s' => $like]); else $stmt->execute();
$usuarios = $stmt->fetchAll();
?>

<h2>Lista de Usuarios</h2>
<form method="get">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por nombre o email">
    <button>Buscar</button>
</form><br>

<table>
    <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Registrado</th><th>Acción</th></tr>
    <?php foreach ($usuarios as $u): ?>
    <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo htmlspecialchars($u['nombre']); ?></td>
        <td><?php echo $u['email']; ?></td>
        <td><?php echo $u['creado_en']; ?></td>
        <td><a href="?delete=<?php echo $u['id']; ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Paginación -->
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
<?php endfor; ?>
</body>
</html>