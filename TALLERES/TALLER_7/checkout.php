<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config_sesion.php';


$compra_realizada = false;
$ultima_compra = [];

if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    $ultima_compra = $_SESSION['carrito'];
    unset($_SESSION['carrito']);
    $compra_realizada = true;
    
   
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        setcookie('username', $username, time() + 86400, '/', '', false, true);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Resumen de Compra</h1>
    <?php if ($compra_realizada): ?>
        <p class="success">¡Compra realizada con éxito!</p>
        <?php if (isset($_COOKIE['username'])): ?>
            <p>Gracias, <?php echo htmlspecialchars($_COOKIE['username']); ?>!</p>
        <?php endif; ?>
      
        <form action="checkout.php" method="post">
            <label>Actualizar nombre de usuario: </label>
            <input type="text" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>">
            <input type="submit" value="Guardar y Confirmar">
        </form>
        
        <h2>Detalles de la compra:</h2>
        <table>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
            <?php
            $total = 0;
            foreach ($ultima_compra as $id => $item):
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                    <td>$<?php echo number_format($item['precio'], 2); ?></td>
                    <td><?php echo $item['cantidad']; ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="total">Total:</td>
                <td class="total">$<?php echo number_format($total, 2); ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>No hay productos para procesar.</p>
    <?php endif; ?>
    <p><a href="productos.php">Volver a Productos</a></p>
</body>
</html>