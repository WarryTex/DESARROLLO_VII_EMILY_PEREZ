<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config_sesion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
    
    if ($producto_id && isset($_SESSION['carrito'][$producto_id])) {
        unset($_SESSION['carrito'][$producto_id]);
        
      
        if (empty($_SESSION['carrito'])) {
            unset($_SESSION['carrito']);
        }
    }
}


header('Location: ver_carrito.php');
exit;
?>