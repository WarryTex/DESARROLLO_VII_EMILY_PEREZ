<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config_sesion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

    if ($producto_id && $cantidad > 0) {
        
        $productos = [
            1 => ['nombre' => 'Camiseta', 'precio' => 19.99],
            2 => ['nombre' => 'Pantalones', 'precio' => 29.99],
            3 => ['nombre' => 'Zapatos', 'precio' => 49.99],
            4 => ['nombre' => 'Chaqueta', 'precio' => 59.99],
            5 => ['nombre' => 'Sombrero', 'precio' => 14.99]
        ];

        if (isset($productos[$producto_id])) {
        
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }

            if (isset($_SESSION['carrito'][$producto_id])) {
                $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
            } else {
                $_SESSION['carrito'][$producto_id] = [
                    'nombre' => $productos[$producto_id]['nombre'],
                    'precio' => $productos[$producto_id]['precio'],
                    'cantidad' => $cantidad
                ];
            }
        }
    }
}

header('Location: productos.php');
exit;
?>