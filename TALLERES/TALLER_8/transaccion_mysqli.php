<?php
require_once "config_mysqli.php";

// Habilitar excepciones en MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function log_error($message) {
    $log_file = 'errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] ERROR: $message" . PHP_EOL;
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

try {
    mysqli_begin_transaction($conn);

    // Insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    $nombre = "Nuevo Usuario";
    $email = "nuevo@example.com";
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $usuario_id = mysqli_insert_id($conn);

    // Insertar una publicación para ese usuario
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    $titulo = "Nueva Publicación";
    $contenido = "Contenido de la nueva publicación";
    mysqli_stmt_bind_param($stmt, "iss", $usuario_id, $titulo, $contenido);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);
    echo "Transacción completada con éxito.";
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conn);
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error en la transacción: $error_msg";
} catch (Exception $e) {
    mysqli_rollback($conn);
    $error_msg = $e->getMessage();
    log_error($error_msg);
    echo "Error inesperado: $error_msg";
}

mysqli_close($conn);
?>