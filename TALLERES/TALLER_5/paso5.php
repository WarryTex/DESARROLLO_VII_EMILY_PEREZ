<?php
// 1. Crear un string JSON con datos de una tienda en línea
$jsonDatos = '
{
    "tienda": "ElectroTech",
    "productos": [
        {"id": 1, "nombre": "Laptop Gamer", "precio": 1200, "categorias": ["electrónica", "computadoras"]},
        {"id": 2, "nombre": "Smartphone 5G", "precio": 800, "categorias": ["electrónica", "celulares"]},
        {"id": 3, "nombre": "Auriculares Bluetooth", "precio": 150, "categorias": ["electrónica", "accesorios"]},
        {"id": 4, "nombre": "Smart TV 4K", "precio": 700, "categorias": ["electrónica", "televisores"]},
        {"id": 5, "nombre": "Tablet", "precio": 300, "categorias": ["electrónica", "computadoras"]}
    ],
    "clientes": [
        {"id": 101, "nombre": "Ana López", "email": "ana@example.com"},
        {"id": 102, "nombre": "Carlos Gómez", "email": "carlos@example.com"},
        {"id": 103, "nombre": "María Rodríguez", "email": "maria@example.com"}
    ]
}
';

// 2. Convertir el JSON a un arreglo asociativo de PHP
$tiendaData = json_decode($jsonDatos, true);

// 3. Función para imprimir los productos
function imprimirProductos($productos) {
    foreach ($productos as $producto) {
        echo "{$producto['nombre']} - \$ {$producto['precio']} - Categorías: " 
            . implode(", ", $producto['categorias']) . "\n";
    }
}

echo "Productos de {$tiendaData['tienda']}:\n";
imprimirProductos($tiendaData['productos']);

// 4. Calcular el valor total del inventario
$valorTotal = array_reduce($tiendaData['productos'], function($total, $producto) {
    return $total + $producto['precio'];
}, 0);

echo "\nValor total del inventario: \$$valorTotal\n";

// 5. Encontrar el producto más caro
$productoMasCaro = array_reduce($tiendaData['productos'], function($max, $producto) {
    return ($producto['precio'] > $max['precio']) ? $producto : $max;
}, $tiendaData['productos'][0]);

// 👇 Línea corregida
echo "\nProducto más caro: {$productoMasCaro['nombre']} (\${$productoMasCaro['precio']})\n";

// 6. Filtrar productos por categoría
function filtrarPorCategoria($productos, $categoria) {
    return array_filter($productos, function($producto) use ($categoria) {
        return in_array($categoria, $producto['categorias']);
    });
}

$productosDeComputadoras = filtrarPorCategoria($tiendaData['productos'], "computadoras");
echo "\nProductos en la categoría 'computadoras':\n";
imprimirProductos($productosDeComputadoras);

// 7. Agregar un nuevo producto
$nuevoProducto = [
    "id" => 6,
    "nombre" => "Smartwatch",
    "precio" => 250,
    "categorias" => ["electrónica", "accesorios", "wearables"]
];
$tiendaData['productos'][] = $nuevoProducto;

// 8. Convertir el arreglo actualizado de vuelta a JSON
$jsonActualizado = json_encode($tiendaData, JSON_PRETTY_PRINT);
echo "\nDatos actualizados de la tienda (JSON):\n$jsonActualizado\n";

// 9. TAREA: Resumen de ventas
$ventas = [
    ["producto_id" => 1, "cliente_id" => 101, "cantidad" => 1, "fecha" => "2025-09-01"],
    ["producto_id" => 2, "cliente_id" => 102, "cantidad" => 2, "fecha" => "2025-09-03"],
    ["producto_id" => 3, "cliente_id" => 103, "cantidad" => 3, "fecha" => "2025-09-04"],
    ["producto_id" => 2, "cliente_id" => 101, "cantidad" => 1, "fecha" => "2025-09-05"],
    ["producto_id" => 5, "cliente_id" => 103, "cantidad" => 2, "fecha" => "2025-09-06"]
];

function generarResumenVentas($ventas, $productos, $clientes) {
    $totalVentas = 0;
    $productosVendidos = [];
    $clientesCompras = [];

    foreach ($ventas as $venta) {
        $totalVentas += $venta["cantidad"];

        // Contar productos vendidos
        if (!isset($productosVendidos[$venta["producto_id"]])) {
            $productosVendidos[$venta["producto_id"]] = 0;
        }
        $productosVendidos[$venta["producto_id"]] += $venta["cantidad"];

        // Contar compras por cliente
        if (!isset($clientesCompras[$venta["cliente_id"]])) {
            $clientesCompras[$venta["cliente_id"]] = 0;
        }
        $clientesCompras[$venta["cliente_id"]] += $venta["cantidad"];
    }

    // Producto más vendido
    arsort($productosVendidos);
    $idProductoTop = array_key_first($productosVendidos);
    $nombreProductoTop = array_values(array_filter($productos, fn($p) => $p["id"] == $idProductoTop))[0]["nombre"];

    // Cliente que más compró
    arsort($clientesCompras);
    $idClienteTop = array_key_first($clientesCompras);
    $nombreClienteTop = array_values(array_filter($clientes, fn($c) => $c["id"] == $idClienteTop))[0]["nombre"];

    return [
        "total_ventas" => $totalVentas,
        "producto_mas_vendido" => $nombreProductoTop,
        "cliente_top" => $nombreClienteTop
    ];
}

echo "\nResumen de ventas:\n";
print_r(generarResumenVentas($ventas, $tiendaData['productos'], $tiendaData['clientes']));
?>
