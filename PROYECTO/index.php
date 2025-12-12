<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'conection/database.php';
require_once BASE_PATH . 'src/producto/productoManager.php';
require_once BASE_PATH . 'src/carrito/carritoManager.php';
require_once BASE_PATH . 'src/usuario/usuarioManager.php';
require_once BASE_PATH . 'src/pedido/pedidoManager.php';

$productoManager = new ProductoManager();

$action = $_GET['action'] ?? 'list';

//Recibe la acción que el usuario quiere hacer, es el indice principal de la aplicación y  decide que código ejecutar.

switch ($action) {

     //Lista de productos.
    case 'list':
        $productos = $productoManager->consultar_todo_los_productos();
        require BASE_PATH . 'views/lista_producto.php';
        break;

  
     //Crea el producto de los datos que vienen del formulario.
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoManager->crearProducto( $_POST['id_categoria'],$_POST['nombre_producto'],$_POST['descripcion_producto'] ?? '',$_POST['precio_producto'],$_FILES['imagen_producto'],$_POST['stock_producto'] ?? 0,$_POST['personalizable'] ?? 0,$_POST['dimensiones'] ?? '',$_POST['status_producto'] ?? 1);
            header('Location: ' . BASE_URL . '/index.php?action=list');
            exit;
        }
        require BASE_PATH . 'views/crear_producto.php';
        break;


    //Editamos el producto por medio de su id.
    case 'edit':

        // Validar ID y obtener producto
       $producto = isset($_GET['id']) ? $productoManager->obtener_producto($_GET['id']) : null;

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

     //Acción de eliminar un producto   
    case 'delete':
        if (isset($_GET['id'])) {
            $productoManager->eliminar_producto($_GET['id']);
        }
        header('Location: ' . BASE_URL . '/index.php?action=list');
        exit;
        break;

     //Para consultar productos por categoria o filtrar/todos-niña-miño
    case 'productos':
    $categoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
    $productos = $productoManager->consultar_productos_por_categoria($categoria);
    require BASE_PATH . 'views/lista_producto.php'; 
    break;

    //detalle de cada producto por separado básica
    case 'detalle':
    $producto = $productoManager->obtener_producto($_GET['id']);
    require BASE_PATH . 'views/detalle_producto.php';
    break;

    //Carrito de la compra recorre cada producto para ver que cantidad hay de cada uno
    case 'carrito':
    $carrito = Carrito::obtener();
    $items_carrito = [];
    $total_precio = 0;
    
        foreach ($carrito as $id_producto => $cantidad) {
        $producto = $productoManager->obtener_producto($id_producto);
        $producto['cantidad'] = $cantidad;
        $items_carrito[] = $producto;
        $total_precio += $producto['precio_producto'] * $cantidad;
    }
    
    require BASE_PATH . 'views/carrito.php';
    break;

    case 'carrito_agregar':
    Carrito::agregar($_GET['id']);
    header('Location: ' . BASE_URL . '/index.php?action=carrito');
    exit;

    case 'carrito_eliminar':
    Carrito::eliminar($_GET['id']);
    header('Location: ' . BASE_URL . '/index.php?action=carrito');
    exit;

    case 'login':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuarioManager = new UsuarioManager();
        $usuario = $usuarioManager->login($_POST['email'], $_POST['contrasena']);
        
        if ($usuario) {
             $_SESSION['usuario'] = $usuario['nombre_completo'];
              $_SESSION['usuario_id'] = $usuario['id_usuario']; 
            header('Location: ' . BASE_URL . '/index.php?action=productos');
            exit;
        } else {
            $error = "Email o contraseña incorrectos";
        }
    }
    require BASE_PATH . 'views/login.php';
    break;

    case 'registro':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuarioManager = new UsuarioManager();
        $usuarioManager->registrar(
            $_POST['nombre_completo'],
            $_POST['telefono'],
            $_POST['email'],
            $_POST['direccion'],
            $_POST['contrasena']
        );
        header('Location: ' . BASE_URL . '/index.php?action=login');
        exit;
    }
    require BASE_PATH . 'views/registro.php';
    break;

    case 'logout':
    session_destroy();
    header('Location: ' . BASE_URL . '/index.php?action=login');
    exit;

    case 'pedidos':
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL . '/index.php?action=login');
        exit;
    }
    
    $pedidoManager = new PedidoManager();
    $pedidos = $pedidoManager->obtener_pedidos_usuario($_SESSION['usuario_id']);
    require BASE_PATH . 'views/pedidos.php';
    break;

    case 'detalle_pedido':
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL . '/index.php?action=login');
        exit;
    }
    
    $pedidoManager = new PedidoManager();
    $detalles = $pedidoManager->obtener_detalle_pedido($_GET['id']);
    
    $total = 0;
    foreach ($detalles as $d) {
        $total += $d['subtotal'];
    }
    
    $id_pedido = $_GET['id'];
    require BASE_PATH . 'views/detalle_pedido.php';
    break;

    default:
        $productos = $productoManager->consultar_todo_los_productos();
        require BASE_PATH . 'views/lista_producto.php';
        break;
}
?>