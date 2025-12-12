<?php ob_start(); ?>

<div class="container mt-5">
    <h2>Mis Pedidos</h2>
    
    <?php if ($pedidos && count($pedidos) > 0): ?>
        
        <?php foreach ($pedidos as $pedido): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Pedido #<?= $pedido['id_pedido'] ?></h5>
                    <p>Fecha: <?= $pedido['fecha_pedido'] ?></p>
                    <p>Total: $<?= number_format($pedido['total_pedido'], 2) ?></p>
                    <p>Estado: <?= $pedido['estado_pedido'] ?></p>
                    
                    <a href="<?= BASE_URL ?>/index.php?action=detalle_pedido&id=<?= $pedido['id_pedido'] ?>" class="btn btn-primary btn-sm">
                        Ver detalles
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        
    <?php else: ?>
        <p>No tienes pedidos aún</p>
        <a href="<?= BASE_URL ?>/index.php?action=productos" class="btn btn-primary">Ver productos</a>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>