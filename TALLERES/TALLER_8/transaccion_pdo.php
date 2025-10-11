<?php
require_once "config_pdo.php";

// Configurar PDO para lanzar excepciones
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function log_error($message) {
    $log_file = 'errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] ERROR: $message" . PHP_EOL;
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

try {
    $pdo->beginTransaction();

    // Insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nombre' => 'Nuevo Usuario', ':email' => 'nuevo@example.com']);
    $usuario_id = $pdo->lastInsertId();

    // Insertar una publicación para ese usuario
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido) VALUES (:usuario_id, :titulo, :contenido)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':titulo' => 'Nueva Publicación',
        ':contenido' => 'Contenido de la nueva publicación'
    ]);

    $pdo->commit();
    echo "Transacción completada con éxito.";
} catch (PDOException $e) {
    $pdo->rollBack();
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error en la transacción: $error_msg";
} catch (Exception $e) {
    $pdo->rollBack();
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error inesperado: $error_msg";
}

$pdo = null;
?>