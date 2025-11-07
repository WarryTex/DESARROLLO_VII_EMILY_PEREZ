<?php
require_once "config_pdo.php";

function filtrarProductos(PDO $pdo, array $criterios) {
    $qb = new QueryBuilder($pdo);
    $qb->table('productos p')
       ->select('p.*', 'c.nombre AS categoria', 's.nombre AS proveedor')
       ->join('categorias c', 'p.categoria_id', '=', 'c.id')
       ->join('proveedores s', 'p.proveedor_id', '=', 's.id');

    if (isset($criterios['precio_min'])) {
        $qb->where('p.precio', '>=', $criterios['precio_min']);
    }
    if (isset($criterios['precio_max'])) {
        $qb->where('p.precio', '<=', $criterios['precio_max']);
    }
    if (isset($criterios['categoria'])) {
        $qb->where('c.id', $criterios['categoria']);
    }
    if (isset($criterios['disponibilidad'])) {
        $qb->where('p.stock', '>', 0);
    }
    if (isset($criterios['ordenar_por'])) {
        $qb->orderBy($criterios['ordenar_por'], $criterios['orden'] ?? 'ASC');
    }
    if (isset($criterios['limite'])) {
        $qb->limit($criterios['limite'], $criterios['offset'] ?? 0);
    }
    return $qb->execute();
}

function generarReporte(PDO $pdo, array $campos, array $filtros) {
    $qb = new QueryBuilder($pdo);
    $qb->table('productos p')
       ->select($campos)
       ->join('categorias c', 'p.categoria_id', '=', 'c.id');

    if (isset($filtros['categoria'])) {
        $qb->where('c.id', $filtros['categoria']);
    }
    if (isset($filtros['precio_min'])) {
        $qb->where('p.precio', '>=', $filtros['precio_min']);
    }
    if (isset($filtros['precio_max'])) {
        $qb->where('p.precio', '<=', $filtros['precio_max']);
    }
    return $qb->execute();
}

function buscarVentas(PDO $pdo, array $criterios) {
    $qb = new QueryBuilder($pdo);
    $qb->table('ventas v')
       ->select('v.id', 'v.fecha', 'v.total', 'c.nombre AS cliente')
       ->join('clientes c', 'v.cliente_id', '=', 'c.id');

    if (isset($criterios['fecha_inicio'])) {
        $qb->where('v.fecha', '>=', $criterios['fecha_inicio']);
    }
    if (isset($criterios['fecha_fin'])) {
        $qb->where('v.fecha', '<=', $criterios['fecha_fin']);
    }
    if (isset($criterios['cliente_id'])) {
        $qb->where('c.id', $criterios['cliente_id']);
    }
    if (isset($criterios['monto_min'])) {
        $qb->where('v.total', '>=', $criterios['monto_min']);
    }
    if (isset($criterios['monto_max'])) {
        $qb->where('v.total', '<=', $criterios['monto_max']);
    }
    if (isset($criterios['limite'])) {
        $qb->limit($criterios['limite'], $criterios['offset'] ?? 0);
    }
    return $qb->execute();
}

function actualizarMasivo(PDO $pdo, array $criterios, array $cambios) {
    $ub = new UpdateBuilder($pdo);
    $ub->table('productos')->set($cambios);

    if (isset($criterios['categoria'])) {
        $ub->where('categoria_id', $criterios['categoria']);
    }
    if (isset($criterios['precio_min'])) {
        $ub->where('precio', '>=', $criterios['precio_min']);
    }
    if (isset($criterios['precio_max'])) {
        $ub->where('precio', '<=', $criterios['precio_max']);
    }
    return $ub->execute();
}

$productos = filtrarProductos($pdo, [
    'precio_min' => 50,
    'precio_max' => 500,
    'categoria' => 2,
    'ordenar_por' => 'p.precio',
    'orden' => 'DESC'
]);

$reporte = generarReporte($pdo, ['p.nombre', 'p.precio', 'c.nombre AS categoria'], [
    'precio_min' => 100
]);

$ventas = buscarVentas($pdo, [
    'fecha_inicio' => '2025-01-01',
    'fecha_fin' => '2025-11-01',
    'monto_min' => 100
]);

$actualizacion = actualizarMasivo($pdo, [
    'categoria' => 1,
    'precio_min' => 20
], [
    'precio' => 29.99
]);

echo "<pre>";
print_r($productos);
print_r($reporte);
print_r($ventas);
print_r($actualizacion);
echo "</pre>";
?>
