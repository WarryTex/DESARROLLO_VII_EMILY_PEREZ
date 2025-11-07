<?php
require_once "config_pdo.php"; // O usar mysqli según prefieras

function verificarCambiosPrecio($pdo, $producto_id, $nuevo_precio) {
    try {
        // Actualizar precio
        $stmt = $pdo->prepare("UPDATE productos SET precio = ? WHERE id = ?");
        $stmt->execute([$nuevo_precio, $producto_id]);
        
        // Verificar log de cambios
        $stmt = $pdo->prepare("SELECT * FROM historial_precios WHERE producto_id = ? ORDER BY fecha_cambio DESC LIMIT 1");
        $stmt->execute([$producto_id]);
        $log = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Cambio de Precio Registrado:</h3>";
        echo "Precio anterior: $" . $log['precio_anterior'] . "<br>";
        echo "Precio nuevo: $" . $log['precio_nuevo'] . "<br>";
        echo "Fecha del cambio: " . $log['fecha_cambio'] . "<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function verificarMovimientoInventario($pdo, $producto_id, $nueva_cantidad) {
    try {
        // Actualizar stock
        $stmt = $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$nueva_cantidad, $producto_id]);
        
        // Verificar movimientos de inventario
        $stmt = $pdo->prepare("
            SELECT * FROM movimientos_inventario 
            WHERE producto_id = ? 
            ORDER BY fecha_movimiento DESC LIMIT 1
        ");
        $stmt->execute([$producto_id]);
        $movimiento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Movimiento de Inventario Registrado:</h3>";
        echo "Tipo de movimiento: " . $movimiento['tipo_movimiento'] . "<br>";
        echo "Cantidad: " . $movimiento['cantidad'] . "<br>";
        echo "Stock anterior: " . $movimiento['stock_anterior'] . "<br>";
        echo "Stock nuevo: " . $movimiento['stock_nuevo'] . "<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Probar los triggers
verificarCambiosPrecio($pdo, 1, 999.99);
verificarMovimientoInventario($pdo, 1, 15);

// 1️⃣ Verificar trigger de membresía
function verificarMembresia($pdo, $cliente_id, $venta_total) {
    echo "<h3>Verificando actualización de membresía...</h3>";
    $pdo->exec("INSERT INTO ventas (cliente_id, total, estado) VALUES ($cliente_id, $venta_total, 'completada')");
    
    $stmt = $pdo->prepare("SELECT nivel_membresia FROM clientes WHERE id = ?");
    $stmt->execute([$cliente_id]);
    $nivel = $stmt->fetchColumn();
    echo "Nuevo nivel de membresía: <strong>$nivel</strong><br>";
}

// 2️⃣ Verificar trigger de estadísticas de categoría
function verificarEstadisticasCategoria($pdo, $producto_id) {
    echo "<h3>Verificando actualización de estadísticas por categoría...</h3>";
    $stmt = $pdo->prepare("INSERT INTO detalles_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (1, ?, 2, 50, 100)");
    $stmt->execute([$producto_id]);
    
    $stmt = $pdo->query("SELECT * FROM estadisticas_categorias LIMIT 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Categoría: {$data['categoria_id']} - Total Ventas: {$data['total_ventas']} - Productos Vendidos: {$data['total_productos_vendidos']}<br>";
}

// 3️⃣ Verificar trigger de alerta de stock
function verificarAlertaStock($pdo, $producto_id) {
    echo "<h3>Verificando alertas por stock bajo...</h3>";
    $stmt = $pdo->prepare("UPDATE productos SET stock = 3 WHERE id = ?");
    $stmt->execute([$producto_id]);
    
    $stmt = $pdo->query("SELECT mensaje FROM alertas_stock ORDER BY fecha_alerta DESC LIMIT 1");
    $alerta = $stmt->fetchColumn();
    echo "Alerta registrada: $alerta<br>";
}

// 4️⃣ Verificar trigger de historial de estado de cliente
function verificarHistorialEstado($pdo, $cliente_id) {
    echo "<h3>Verificando historial de cambios de estado...</h3>";
    $pdo->exec("UPDATE clientes SET estado = IF(estado='activo','inactivo','activo') WHERE id = $cliente_id");
    
    $stmt = $pdo->query("SELECT * FROM historial_estado_clientes ORDER BY fecha_cambio DESC LIMIT 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Cambio registrado: {$data['estado_anterior']} → {$data['estado_nuevo']}<br>";
}

// Llamadas de prueba
verificarMembresia($pdo, 1, 2500);
verificarEstadisticasCategoria($pdo, 1);
verificarAlertaStock($pdo, 1);
verificarHistorialEstado($pdo, 1);


$pdo = null;
?>
        