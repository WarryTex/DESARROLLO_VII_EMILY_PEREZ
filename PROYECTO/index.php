<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'conection/database.php';
require_once BASE_PATH . 'src/producto/productoManager.php';

$productoManager = new ProductoManager();

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $productos = $productoManager->consultar_todo_los_productos();
        require BASE_PATH . 'views/lista_producto.php';
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoManager->crearProducto(
                $_POST['id_categoria'],
                $_POST['nombre_producto'],
                $_POST['descripcion_producto'] ?? '',
                $_POST['precio_producto'],
                $_FILES['imagen_producto'],      
                $_POST['stock_producto'] ?? 0,
                $_POST['personalizable'] ?? 0,
                $_POST['dimensiones'] ?? '',
                $_POST['status_producto'] ?? 1
            );

            header('Location: ' . BASE_URL . '/index.php?action=list');
            exit;
        }
        require BASE_PATH . 'views/crear_producto.php';
        break;

    case 'edit':
        if (!isset($_GET['id'])) {
            header('Location: ' . BASE_URL . '/index.php?action=list');
            exit;
        }

        $producto = $productoManager->obtener_producto($_GET['id']);
        
        if (!$producto) {
            header('Location: ' . BASE_URL . '/index.php?action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoManager->actualizarProducto(
                $_GET['id'],
                $_POST['id_categoria'],
                $_POST['nombre_producto'],
                $_POST['descripcion_producto'] ?? '',
                $_POST['precio_producto'],
                $_FILES['imagen_producto'],      
                $_POST['stock_producto'] ?? 0,
                $_POST['personalizable'] ?? 0,
                $_POST['dimensiones'] ?? '',
                $_POST['status_producto'] ?? 1
            );

            header('Location: ' . BASE_URL . '/index.php?action=list');
            exit;
        }
        require BASE_PATH . 'views/editar_producto.php';
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $productoManager->eliminar_producto($_GET['id']);
        }
        header('Location: ' . BASE_URL . '/index.php?action=list');
        exit;
        break;

    default:
        $productos = $productoManager->consultar_todo_los_productos();
        require BASE_PATH . 'views/lista_producto.php';
        break;
}
?>