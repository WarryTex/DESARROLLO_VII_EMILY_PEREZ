<?php
const RUTA_INVENTARIO = __DIR__ . DIRECTORY_SEPARATOR . 'inventario.json';

/** Leer inventario desde JSON */
function leerInventario(string $ruta): array {
    if (!file_exists($ruta)) {
        throw new RuntimeException("No se encontró el archivo: $ruta");
    }
    $contenido = file_get_contents($ruta);
    if ($contenido === false) {
        throw new RuntimeException("No se pudo leer el archivo: $ruta");
    }
    $datos = json_decode($contenido, true);
    if (!is_array($datos)) {
        throw new RuntimeException("El JSON es inválido o no contiene un array de productos.");
    }
    return $datos;
}

/** Ordenar por nombre (A-Z) */
function ordenarPorNombre(array $productos): array {
    usort($productos, function ($a, $b) {
        return strcasecmp($a['nombre'] ?? '', $b['nombre'] ?? '');
    });
    return $productos;
}

function mostrarResumen(array $productos): void {
    echo "\n=== Resumen del inventario (ordenado A-Z) ===\n";
    printf("%-20s | %-10s | %-9s | %-10s\n", "Nombre", "Precio", "Cantidad", "Subtotal");
    echo str_repeat("-", 60) . "\n";

    array_map(function($p) {
        $nombre   = (string)($p['nombre'] ?? '');
        $precio   = (float)($p['precio'] ?? 0);
        $cantidad = (int)($p['cantidad'] ?? 0);
        $subtotal = $precio * $cantidad;
        printf("%-20s | %10.2f | %9d | %10.2f\n", $nombre, $precio, $cantidad, $subtotal);
    }, $productos);
}

function calcularValorTotal(array $productos): float {
    $subtotales = array_map(function($p) {
        $precio   = (float)($p['precio'] ?? 0);
        $cantidad = (int)($p['cantidad'] ?? 0);
        return $precio * $cantidad;
    }, $productos);
    return array_sum($subtotales);
}

function informeStockBajo(array $productos, int $umbral = 5): array {
    return array_values(array_filter($productos, function($p) use ($umbral) {
        return isset($p['cantidad']) && (int)$p['cantidad'] < $umbral;
    }));
}

try {
    $inventario = leerInventario(RUTA_INVENTARIO);
    $inventarioOrdenado = ordenarPorNombre($inventario);
    mostrarResumen($inventarioOrdenado);

    $total = calcularValorTotal($inventarioOrdenado);
    echo str_repeat("-", 60) . "\n";
    echo "Valor total del inventario: " . number_format($total, 2) . PHP_EOL;

    $bajoStock = informeStockBajo($inventarioOrdenado, 5);
    echo "\n=== Informe de productos con stock bajo (menos de 5) ===\n";
    if (empty($bajoStock)) {
        echo "No hay productos con stock bajo.\n";
    } else {
        foreach ($bajoStock as $p) {
            printf("- %s (cantidad: %d)\n", $p['nombre'], (int)$p['cantidad']);
        }
    }

    echo "\nListo.\n";

} catch (Throwable $e) {
    fwrite(STDERR, "Error: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
?>
