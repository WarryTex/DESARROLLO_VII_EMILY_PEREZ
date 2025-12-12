<?php ob_start(); ?>

<div class="container mt-5">
    <h2>Productos del Pedido #<?= $_GET['id'] ?></h2>
    
    <?php foreach ($detalles as $d): ?>
        <?php 
        $imagen = $d['imagen_producto'] 
            ? BASE_URL . '/public/assets/img/' . $d['imagen_producto']
            : BASE_URL . '/public/assets/img/no-image.png';
        ?>
        
        <div class="card mb-3">
            <div class="card-body">
                <div style="display: flex;">
                    <img src="<?= $imagen ?>" style="width: 200px; height: 200px; object-fit: cover; margin-right: 20px;" alt="">
                    
                    <div>
                        <p><strong>Nombre:</strong> <?= $d['nombre_producto'] ?></p>
                        <p><strong>Cantidad:</strong> <?= $d['cantidad'] ?></p>
                        <p><strong>Precio:</strong> $<?= $d['precio_unitario'] ?></p>
                        <p><strong>Subtotal:</strong> $<?= $d['subtotal'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <a href="<?= BASE_URL ?>/index.php?action=pedidos" class="btn btn-secondary mt-3">Volver</a>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>