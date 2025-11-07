<?php
require_once "config_pdo.php";

// 1️ Productos con bajo stock
function mostrarProductosBajoStock($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vista_productos_bajo_stock");
        echo "<h3>Productos con Bajo Stock:</h3>";
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Total Vendido</th>
                        <th>Ingresos Totales</th>
                    </tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['producto']}</td>
                        <td>{$row['categoria']}</td>
                        <td>{$row['stock']}</td>
                        <td>{$row['total_vendido']}</td>
                        <td>${$row['total_ingresos']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No hay productos con bajo stock.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// 2️ Historial de clientes
function mostrarHistorialClientes($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vista_historial_clientes");
        echo "<h3>Historial de Clientes:</h3>";
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Fecha Venta</th>
                        <th>Total Venta</th>
                    </tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['cliente']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['producto']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>${$row['subtotal']}</td>
                        <td>{$row['fecha_venta']}</td>
                        <td>${$row['total_venta']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No hay registros en el historial de clientes.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// 3️ Rendimiento por categoría
function mostrarRendimientoCategorias($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vista_rendimiento_categorias");
        echo "<h3>Métricas de Rendimiento por Categoría:</h3>";
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Categoría</th>
                        <th>Total Productos</th>
                        <th>Unidades Vendidas</th>
                        <th>Ingresos Totales</th>
                        <th>Producto Más Vendido</th>
                    </tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['categoria']}</td>
                        <td>{$row['total_productos']}</td>
                        <td>{$row['unidades_vendidas']}</td>
                        <td>${$row['ingresos_totales']}</td>
                        <td>{$row['producto_mas_vendido']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No hay métricas disponibles.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// 4️ Tendencias de ventas
function mostrarTendenciasVentas($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vista_tendencias_ventas");
        echo "<h3>Tendencias de Ventas por Mes:</h3>";
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Mes</th>
                        <th>Total Ventas</th>
                        <th>Ingresos Totales</th>
                        <th>Ingresos Mes Anterior</th>
                        <th>Variación (%)</th>
                    </tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['mes']}</td>
                        <td>{$row['total_ventas']}</td>
                        <td>${$row['ingresos_totales']}</td>
                        <td>${$row['ingresos_mes_anterior']}</td>
                        <td>{$row['variacion_porcentual']}%</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No hay datos de tendencias disponibles.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Mostrar todo
mostrarProductosBajoStock($pdo);
mostrarHistorialClientes($pdo);
mostrarRendimientoCategorias($pdo);
mostrarTendenciasVentas($pdo);

$pdo = null;
?>
