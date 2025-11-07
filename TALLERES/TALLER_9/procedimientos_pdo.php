<?php
require_once "config_pdo.php";

// Función para registrar una venta
function registrarVenta($pdo, $cliente_id, $producto_id, $cantidad) {
    try {
        $stmt = $pdo->prepare("CALL sp_registrar_venta(:cliente_id, :producto_id, :cantidad, @venta_id)");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        
        // Obtener el ID de la venta
        $result = $pdo->query("SELECT @venta_id as venta_id")->fetch(PDO::FETCH_ASSOC);
        
        echo "Venta registrada con éxito. ID de venta: " . $result['venta_id'];
    } catch (PDOException $e) {
        echo "Error al registrar la venta: " . $e->getMessage();
    }
}

// Función para obtener estadísticas de cliente
function obtenerEstadisticasCliente($pdo, $cliente_id) {
    try {
        $stmt = $pdo->prepare("CALL sp_estadisticas_cliente(:cliente_id)");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Estadísticas del Cliente</h3>";
        echo "Nombre: " . $estadisticas['nombre'] . "<br>";
        echo "Membresía: " . $estadisticas['nivel_membresia'] . "<br>";
        echo "Total compras: " . $estadisticas['total_compras'] . "<br>";
        echo "Total gastado: $" . $estadisticas['total_gastado'] . "<br>";
        echo "Promedio de compra: $" . $estadisticas['promedio_compra'] . "<br>";
        echo "Últimos productos: " . $estadisticas['ultimos_productos'] . "<br>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Ejemplos de uso
registrarVenta($pdo, 1, 1, 2);
obtenerEstadisticasCliente($pdo, 1);


// Procesar devolución
function procesarDevolucion($pdo, $venta_id, $producto_id, $cantidad) {
    $stmt = $pdo->prepare("CALL sp_procesar_devolucion(:venta_id, :producto_id, :cantidad)");
    $stmt->execute([':venta_id'=>$venta_id, ':producto_id'=>$producto_id, ':cantidad'=>$cantidad]);
    echo "Devolución procesada correctamente.<br>";
}

// Aplicar descuento
function aplicarDescuentoCliente($pdo, $cliente_id) {
    $stmt = $pdo->prepare("CALL sp_aplicar_descuento_cliente(:cliente_id)");
    $stmt->execute([':cliente_id'=>$cliente_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row['resultado'] . "<br>";
}

// Reporte bajo stock
function reporteBajoStock($pdo) {
    $stmt = $pdo->query("CALL sp_reporte_bajo_stock()");
    echo "<h3>Productos con Bajo Stock</h3><table border='1'><tr><th>Producto</th><th>Stock</th><th>Sugerido</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>{$row['producto']}</td><td>{$row['stock']}</td><td>{$row['sugerido_reposicion']}</td></tr>";
    }
    echo "</table>";
}

// Calcular comisiones
function calcularComisiones($pdo) {
    $stmt = $pdo->query("CALL sp_calcular_comisiones()");
    echo "<h3>Comisiones por Ventas</h3><table border='1'><tr><th>ID Venta</th><th>Total</th><th>Comisión</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>{$row['venta_id']}</td><td>{$row['total']}</td><td>{$row['comision']}</td></tr>";
    }
    echo "</table>";
}

// Ejemplos de uso
procesarDevolucion($pdo, 1, 1, 1);
aplicarDescuentoCliente($pdo, 1);
reporteBajoStock($pdo);
calcularComisiones($pdo);

$pdo = null;
?>
        