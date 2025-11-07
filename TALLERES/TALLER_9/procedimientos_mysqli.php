<?php
require_once "config_mysqli.php";

// Función para registrar una venta
function registrarVenta($conn, $cliente_id, $producto_id, $cantidad) {
    $query = "CALL sp_registrar_venta(?, ?, ?, @venta_id)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $cliente_id, $producto_id, $cantidad);
    
    try {
        mysqli_stmt_execute($stmt);
        
        // Obtener el ID de la venta
        $result = mysqli_query($conn, "SELECT @venta_id as venta_id");
        $row = mysqli_fetch_assoc($result);
        
        echo "Venta registrada con éxito. ID de venta: " . $row['venta_id'];
    } catch (Exception $e) {
        echo "Error al registrar la venta: " . $e->getMessage();
    }
    
    mysqli_stmt_close($stmt);
}

// Función para obtener estadísticas de cliente
function obtenerEstadisticasCliente($conn, $cliente_id) {
    $query = "CALL sp_estadisticas_cliente(?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $cliente_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $estadisticas = mysqli_fetch_assoc($result);
        
        echo "<h3>Estadísticas del Cliente</h3>";
        echo "Nombre: " . $estadisticas['nombre'] . "<br>";
        echo "Membresía: " . $estadisticas['nivel_membresia'] . "<br>";
        echo "Total compras: " . $estadisticas['total_compras'] . "<br>";
        echo "Total gastado: $" . $estadisticas['total_gastado'] . "<br>";
        echo "Promedio de compra: $" . $estadisticas['promedio_compra'] . "<br>";
        echo "Últimos productos: " . $estadisticas['ultimos_productos'] . "<br>";
    }
    
    mysqli_stmt_close($stmt);
}

// Ejemplos de uso
registrarVenta($conn, 1, 1, 2);
obtenerEstadisticasCliente($conn, 1);

// Procesar devolución
function procesarDevolucion($conn, $venta_id, $producto_id, $cantidad) {
    $stmt = mysqli_prepare($conn, "CALL sp_procesar_devolucion(?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iii", $venta_id, $producto_id, $cantidad);
    mysqli_stmt_execute($stmt);
    echo "Devolución procesada correctamente.<br>";
    mysqli_stmt_close($stmt);
}

// Aplicar descuento
function aplicarDescuentoCliente($conn, $cliente_id) {
    $stmt = mysqli_prepare($conn, "CALL sp_aplicar_descuento_cliente(?)");
    mysqli_stmt_bind_param($stmt, "i", $cliente_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    echo $row['resultado'] . "<br>";
    mysqli_stmt_close($stmt);
}

// Reporte bajo stock
function reporteBajoStock($conn) {
    $result = mysqli_query($conn, "CALL sp_reporte_bajo_stock()");
    echo "<h3>Productos con Bajo Stock</h3><table border='1'><tr><th>Producto</th><th>Stock</th><th>Sugerido</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['producto']}</td><td>{$row['stock']}</td><td>{$row['sugerido_reposicion']}</td></tr>";
    }
    echo "</table>";
}

// Calcular comisiones
function calcularComisiones($conn) {
    $result = mysqli_query($conn, "CALL sp_calcular_comisiones()");
    echo "<h3>Comisiones por Ventas</h3><table border='1'><tr><th>ID Venta</th><th>Total</th><th>Comisión</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['venta_id']}</td><td>{$row['total']}</td><td>{$row['comision']}</td></tr>";
    }
    echo "</table>";
}

// Ejemplos de uso
procesarDevolucion($conn, 1, 1, 1);
aplicarDescuentoCliente($conn, 1);
reporteBajoStock($conn);
calcularComisiones($conn);


mysqli_close($conn);
?>
        