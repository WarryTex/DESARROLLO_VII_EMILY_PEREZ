<?php ob_start(); ?>

<div class="container mt-5 text-center">
    <h1 class="text-success">✓ Pedido realizado</h1>
    <p>Tu pedido #<?= $id_pedido ?> fue procesado correctamente</p>
    <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
    
    <a href="<?= BASE_URL ?>/index.php?action=pedidos" class="btn btn-primary">Ver mis pedidos</a>
    <a href="<?= BASE_URL ?>/index.php?action=productos" class="btn btn-secondary">Seguir comprando</a>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>