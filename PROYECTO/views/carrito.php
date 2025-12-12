<?php ob_start(); ?>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        
        <h2 class="fw-bold mb-4">Mi Carrito</h2>
        
        <?php if ($items_carrito && count($items_carrito) > 0): ?>
            
            <?php foreach ($items_carrito as $item): ?>
                <?php 
                $imagen = $item['imagen_producto'] 
                    ? BASE_URL . '/public/assets/img/' . $item['imagen_producto']
                    : BASE_URL . '/public/assets/img/no-image.png';
                
                $subtotal = $item['precio_producto'] * $item['cantidad'];
                ?>
                
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="<?= $imagen ?>" class="img-fluid rounded" alt="">
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h5><?= $item['nombre_producto'] ?></h5>
                                <p>Precio: $<?= number_format($item['precio_producto'], 2) ?></p>
                                <p>Cantidad: <?= $item['cantidad'] ?></p>
                                <p><strong>Subtotal: $<?= number_format($subtotal, 2) ?></strong></p>
                                <a href="<?= BASE_URL ?>/index.php?action=carrito_eliminar&id=<?= $item['id_producto'] ?>" class="btn btn-danger btn-sm">
                                    Eliminar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php endforeach; ?>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h4>Total: $<?= number_format($total_precio, 2) ?></h4>
                  <a href="<?= BASE_URL ?>/index.php?action=pasarela_pago" class="btn btn-primary">Proceder al pago</a>
                    <a href="<?= BASE_URL ?>/index.php?action=productos" class="btn btn-secondary">Seguir comprando</a>
                </div>
            </div>
            
        <?php else: ?>
            <p>Tu carrito está vacío</p>
            <a href="<?= BASE_URL ?>/index.php?action=productos" class="btn btn-primary">Ver productos</a>
        <?php endif; ?>
        
    </div>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>