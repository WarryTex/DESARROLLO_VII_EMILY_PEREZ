<?php
require_once "config_mysqli.php";

// 1. Productos que tienen un precio mayor al promedio de su categoría
$sql = "SELECT p.nombre, p.precio, c.nombre AS categoria,
               (SELECT AVG(precio) FROM productos WHERE categoria_id = p.categoria_id) AS promedio_categoria
        FROM productos p
        JOIN categorias c ON p.categoria_id = c.id
        WHERE p.precio > (
            SELECT AVG(precio)
            FROM productos p2
            WHERE p2.categoria_id = p.categoria_id
        )";
if ($result = mysqli_query($conn, $sql)) {
    echo "<h3>1. Productos con precio mayor al promedio de su categoría:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Producto: {$row['nombre']}, Precio: {$row['precio']}, Categoría: {$row['categoria']}, Promedio categoría: {$row['promedio_categoria']}<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error (1): " . mysqli_error($conn) . "<br>";
}

// 2. Clientes con compras superiores al promedio
$sql = "SELECT c.nombre, c.email,
               (SELECT IFNULL(SUM(total),0) FROM ventas WHERE cliente_id = c.id) AS total_compras,
               (SELECT IFNULL(AVG(total),0) FROM ventas) AS promedio_ventas
        FROM clientes c
        WHERE (
            SELECT IFNULL(SUM(total),0)
            FROM ventas
            WHERE cliente_id = c.id
        ) > (
            SELECT IFNULL(AVG(total),0)
            FROM ventas
        )";
if ($result = mysqli_query($conn, $sql)) {
    echo "<h3>2. Clientes con compras superiores al promedio:</h3>";
    if (mysqli_num_rows($result) === 0) {
        echo "Ningún cliente supera el promedio de compras.<br>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Cliente: {$row['nombre']}, Total compras: {$row['total_compras']}, Promedio general: {$row['promedio_ventas']}<br>";
        }
    }
    mysqli_free_result($result);
} else {
    echo "Error (2): " . mysqli_error($conn) . "<br>";
}

// 3. Productos que nunca se han vendido
$sql = "SELECT p.id, p.nombre, p.precio, c.nombre AS categoria
        FROM productos p
        JOIN categorias c ON p.categoria_id = c.id
        WHERE p.id NOT IN (
            SELECT DISTINCT dv.producto_id FROM detalle_ventas dv WHERE dv.producto_id IS NOT NULL
        )";
if ($result = mysqli_query($conn, $sql)) {
    echo "<h3>3. Productos que nunca se han vendido:</h3>";
    if (mysqli_num_rows($result) === 0) {
        echo "Todos los productos han sido vendidos al menos una vez.<br>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Producto: {$row['nombre']} - Categoría: {$row['categoria']} - Precio: {$row['precio']}<br>";
        }
    }
    mysqli_free_result($result);
} else {
    echo "Error (3): " . mysqli_error($conn) . "<br>";
}

// 4. Categorías con número de productos y valor total del inventario
$sql = "SELECT c.id AS categoria_id, c.nombre AS categoria,
               COUNT(p.id) AS total_productos,
               IFNULL(SUM(p.precio * IFNULL(p.stock,0)), 0) AS valor_inventario
        FROM categorias c
        LEFT JOIN productos p ON c.id = p.categoria_id
        GROUP BY c.id, c.nombre";
if ($result = mysqli_query($conn, $sql)) {
    echo "<h3>4. Categorías con número de productos y valor total del inventario:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Categoría: {$row['categoria']} - Productos: {$row['total_productos']} - Valor total: {$row['valor_inventario']}<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error (4): " . mysqli_error($conn) . "<br>";
}

// 5. Clientes que han comprado todos los productos de una categoría específica
$categoria_especifica = 1; // cambiar según sea necesario
$sql = "SELECT c.nombre AS cliente
        FROM clientes c
        WHERE NOT EXISTS (
            SELECT p.id FROM productos p
            WHERE p.categoria_id = ?
            AND p.id NOT IN (
                SELECT dv.producto_id
                FROM ventas v
                JOIN detalle_ventas dv ON v.id = dv.venta_id
                WHERE v.cliente_id = c.id
            )
        )";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $categoria_especifica);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    echo "<h3>5. Clientes que han comprado todos los productos de la categoría #{$categoria_especifica}:</h3>";
    if ($res && mysqli_num_rows($res) === 0) {
        echo "Ningún cliente ha comprado todos los productos de esta categoría.<br>";
    } else {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "Cliente: {$row['cliente']}<br>";
        }
    }
    if ($res) mysqli_free_result($res);
    mysqli_stmt_close($stmt);
} else {
    echo "Error (5 - prepare): " . mysqli_error($conn) . "<br>";
}

// 6. Porcentaje de ventas de cada producto respecto al total de ventas
$sql = "SELECT p.id, p.nombre,
               IFNULL(SUM(dv.cantidad * dv.precio_unitario), 0) AS total_producto,
               ROUND(
                   (IFNULL(SUM(dv.cantidad * dv.precio_unitario),0) /
                    NULLIF((SELECT IFNULL(SUM(cantidad * precio_unitario),0) FROM detalle_ventas),0)
                   ) * 100, 2
               ) AS porcentaje_ventas
        FROM productos p
        LEFT JOIN detalle_ventas dv ON p.id = dv.producto_id
        GROUP BY p.id, p.nombre";
if ($result = mysqli_query($conn, $sql)) {
    echo "<h3>6. Porcentaje de ventas de cada producto respecto al total de ventas:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        // Manejar NULL en porcentaje (por ejemplo cuando total general = 0)
        $porcentaje = is_null($row['porcentaje_ventas']) ? 0 : $row['porcentaje_ventas'];
        echo "Producto: {$row['nombre']} - Total vendido: {$row['total_producto']} - Porcentaje: {$porcentaje}%<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error (6): " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>
