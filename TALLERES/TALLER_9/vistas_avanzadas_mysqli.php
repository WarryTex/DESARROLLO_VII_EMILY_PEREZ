<?php
require_once "config_mysqli.php";

// 1️.Productos con bajo stock
function mostrarProductosBajoStock($conn) {
    $sql = "SELECT * FROM vista_productos_bajo_stock";
    $result = mysqli_query($conn, $sql);

    echo "<h3>Productos con Bajo Stock:</h3>";
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Total Vendido</th>
                    <th>Ingresos Totales</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
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
    mysqli_free_result($result);
}

// 2 Historial completo de clientes
function mostrarHistorialClientes($conn) {
    $sql = "SELECT * FROM vista_historial_clientes";
    $result = mysqli_query($conn, $sql);

    echo "<h3>Historial de Clientes:</h3>";
    if (mysqli_num_rows($result) > 0) {
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
        while ($row = mysqli_fetch_assoc($result)) {
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
    mysqli_free_result($result);
}

// 3️ Rendimiento por categoría
function mostrarRendimientoCategorias($conn) {
    $sql = "SELECT * FROM vista_rendimiento_categorias";
    $result = mysqli_query($conn, $sql);

    echo "<h3>Métricas de Rendimiento por Categoría:</h3>";
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Categoría</th>
                    <th>Total Productos</th>
                    <th>Unidades Vendidas</th>
                    <th>Ingresos Totales</th>
                    <th>Producto Más Vendido</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
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
    mysqli_free_result($result);
}

// 4️ Tendencias de ventas por mes
function mostrarTendenciasVentas($conn) {
    $sql = "SELECT * FROM vista_tendencias_ventas";
    $result = mysqli_query($conn, $sql);

    echo "<h3>Tendencias de Ventas por Mes:</h3>";
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Mes</th>
                    <th>Total Ventas</th>
                    <th>Ingresos Totales</th>
                    <th>Ingresos Mes Anterior</th>
                    <th>Variación (%)</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
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
    mysqli_free_result($result);
}

// Mostrar todo
mostrarProductosBajoStock($conn);
mostrarHistorialClientes($conn);
mostrarRendimientoCategorias($conn);
mostrarTendenciasVentas($conn);

mysqli_close($conn);
?>
