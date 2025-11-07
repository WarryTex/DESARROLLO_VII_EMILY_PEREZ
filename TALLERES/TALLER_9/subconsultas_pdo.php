<?php
require_once "config_pdo.php";

try {
    // 1. Productos que tienen un precio mayor al promedio de su categoría
    $sql = "SELECT p.nombre, p.precio, c.nombre as categoria,
            (SELECT AVG(precio) FROM productos WHERE categoria_id = p.categoria_id) as promedio_categoria
            FROM productos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.precio > (
                SELECT AVG(precio)
                FROM productos p2
                WHERE p2.categoria_id = p.categoria_id
            )";

    $stmt = $pdo->query($sql);
    echo "<h3>1. Productos con precio mayor al promedio de su categoría:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Producto: {$row['nombre']}, Precio: ${$row['precio']}, ";
        echo "Categoría: {$row['categoria']}, Promedio categoría: ${$row['promedio_categoria']}<br>";
    }

    // 2. Clientes con compras superiores al promedio
    $sql = "SELECT c.nombre, c.email,
            (SELECT SUM(total) FROM ventas WHERE cliente_id = c.id) as total_compras,
            (SELECT AVG(total) FROM ventas) as promedio_ventas
            FROM clientes c
            WHERE (
                SELECT SUM(total)
                FROM ventas
                WHERE cliente_id = c.id
            ) > (
                SELECT AVG(total)
                FROM ventas
            )";

    $stmt = $pdo->query($sql);
    echo "<h3>2. Clientes con compras superiores al promedio:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Cliente: {$row['nombre']}, Total compras: ${$row['total_compras']}, ";
        echo "Promedio general: ${$row['promedio_ventas']}<br>";
    }

    // 3. Productos que nunca se han vendido
    $sql = "SELECT p.id, p.nombre, p.precio, c.nombre AS categoria
            FROM productos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.id NOT IN (
                SELECT DISTINCT producto_id FROM detalle_ventas
            )";
    $stmt = $pdo->query($sql);
    echo "<h3>3. Productos que nunca se han vendido:</h3>";
    if ($stmt->rowCount() === 0) {
        echo "Todos los productos han sido vendidos.<br>";
    } else {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Producto: {$row['nombre']} - Categoría: {$row['categoria']} - Precio: ${$row['precio']}<br>";
        }
    }

    // 4. Categorías con número de productos y valor total del inventario
    $sql = "SELECT c.nombre AS categoria,
                   COUNT(p.id) AS total_productos,
                   IFNULL(SUM(p.precio * p.stock), 0) AS valor_inventario
            FROM categorias c
            LEFT JOIN productos p ON c.id = p.categoria_id
            GROUP BY c.id, c.nombre";
    $stmt = $pdo->query($sql);
    echo "<h3>4. Categorías con número de productos y valor total del inventario:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Categoría: {$row['categoria']} - Productos: {$row['total_productos']} - Valor total: ${$row['valor_inventario']}<br>";
    }

    // 5. Clientes que han comprado todos los productos de una categoría específica
    $categoria_especifica = 1; // puedes cambiar el ID de la categoría
    $sql = "SELECT c.nombre AS cliente
            FROM clientes c
            WHERE NOT EXISTS (
                SELECT p.id FROM productos p
                WHERE p.categoria_id = :categoria
                AND p.id NOT IN (
                    SELECT dv.producto_id
                    FROM ventas v
                    JOIN detalle_ventas dv ON v.id = dv.venta_id
                    WHERE v.cliente_id = c.id
                )
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['categoria' => $categoria_especifica]);
    echo "<h3>5. Clientes que han comprado todos los productos de la categoría #$categoria_especifica:</h3>";
    if ($stmt->rowCount() === 0) {
        echo "Ningún cliente ha comprado todos los productos de esta categoría.<br>";
    } else {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Cliente: {$row['cliente']}<br>";
        }
    }

    // 6. Porcentaje de ventas de cada producto respecto al total de ventas
    $sql = "SELECT p.nombre,
                   IFNULL(SUM(dv.cantidad * dv.precio_unitario), 0) AS total_producto,
                   ROUND(
                       (IFNULL(SUM(dv.cantidad * dv.precio_unitario), 0) /
                       (SELECT SUM(cantidad * precio_unitario) FROM detalle_ventas)) * 100, 2
                   ) AS porcentaje_ventas
            FROM productos p
            LEFT JOIN detalle_ventas dv ON p.id = dv.producto_id
            GROUP BY p.id, p.nombre";
    $stmt = $pdo->query($sql);
    echo "<h3>6. Porcentaje de ventas de cada producto respecto al total de ventas:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Producto: {$row['nombre']} - Total vendido: ${$row['total_producto']} - Porcentaje: {$row['porcentaje_ventas']}%<br>";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
