<?php ob_start(); ?>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        
        <?php if ($producto): ?>
            
            <div class="row">
                <!-- Imagen -->
                <div class="col-md-6">
                    <?php 
                    $imagen = $producto['imagen_producto'] 
                        ? BASE_URL . '/public/assets/img/' . $producto['imagen_producto']
                        : BASE_URL . '/public/assets/img/no-image.png';
                    ?>
                    <img src="<?= $imagen ?>" class="img-fluid rounded" alt="<?= $producto['nombre_producto'] ?>">
                </div>
                
                <!-- Información -->
                <div class="col-md-6">
                    <h1><?= $producto['nombre_producto'] ?></h1>
                    <h3 class="text-primary">$<?= number_format($producto['precio_producto'], 2) ?></h3>
                    
                    <p><?= $producto['descripcion_producto'] ?></p>
                    
                    <p><strong>Stock:</strong> <?= $producto['stock_producto'] ?> unidades</p>
                    
                    <?php if ($producto['dimensiones']): ?>
                    <p><strong>Dimensiones:</strong> <?= $producto['dimensiones'] ?></p>
                    <?php endif; ?>
                    
                    <p><strong>Personalizable:</strong> <?= $producto['personalizable'] ? 'Sí' : 'No' ?></p>
                    
                   <a href="<?= BASE_URL ?>/index.php?action=carrito_agregar&id=<?= $producto['id_producto'] ?>" class="btn btn-primary btn-lg">
                   Agregar al carrito
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?action=productos" class="btn btn-secondary">Volver</a>
                </div>
            </div>
            
        <?php else: ?>
            <p>Producto no encontrado</p>
        <?php endif; ?>
        
    </div>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>