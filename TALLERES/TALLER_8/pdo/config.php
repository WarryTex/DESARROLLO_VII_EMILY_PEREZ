
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'biblioteca');

function log_error($message) {
    $log_file = 'errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] ERROR: $message" . PHP_EOL;
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_msg = "Error de conexión: " . $e->getMessage();
    log_error($error_msg);
    die($error_msg);
}
?>
