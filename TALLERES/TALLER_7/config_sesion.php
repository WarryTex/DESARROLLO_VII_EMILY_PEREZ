<?php
session_save_path('C:/laragon/tmp');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de sesión segura
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); 

session_start([
    'cookie_lifetime' => 86400, 
    'cookie_secure' => false,   
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);
?>