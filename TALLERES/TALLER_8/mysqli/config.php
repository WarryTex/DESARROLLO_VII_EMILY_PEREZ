<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'taller8_db');

function log_error($message) {
    $log_file = 'errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] ERROR: $message" . PHP_EOL;
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    $error_msg = "Error de conexión: " . mysqli_connect_error();
    log_error($error_msg);
    die($error_msg);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_set_charset($conn, 'utf8');
?>
