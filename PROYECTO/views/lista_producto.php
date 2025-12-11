<?php ob_start(); ?>

<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Nuestros Productos</h2>
            <a href="<?= BASE_URL ?>/index.php?action=create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Agregar Producto
            </a>
        </div>

        <?php if ($productos && count($productos) > 0): ?>
            
            <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-xl-4 justify-content-center">
                
                <?php foreach ($productos as $p): ?>
                    <?php
                    $imagen_url = $p['imagen_producto'] 
                        ? BASE_URL . '/public/assets/img/' . htmlspecialchars($p['imagen_producto'])
                        : BASE_URL . '/public/assets/img/no-image.png';
                    
                    $categoria = '';
                    switch($p['id_categoria']) {
                        case 1: $categoria = 'Niña'; break;
                        case 2: $categoria = 'Niño'; break;
                        default: $categoria = 'Sin categoría';
                    }
                    
                 
                    $tiene_descuento = $p['stock_producto'] < 10;
                    $precio_original = $p['precio_producto'];
                    $precio_descuento = $tiene_descuento ? $precio_original * 0.75 : $precio_original;
                    
                   
                    ?>
                    
                    <div class="col mb-5">
                        <div class="card h-100 shadow-sm">
                            
                          
                            <img class="card-img-top" src="<?= $imagen_url ?>" alt="<?= htmlspecialchars($p['nombre_producto']) ?>" style="height: 300px; object-fit: cover;" />
                            
                          
                            <div class="card-body p-4">
                                <div class="text-center">
                                   
                                    <small class="text-muted"><?= $categoria ?></small>
                              
                                    <h5 class="fw-bolder mt-2"><?= htmlspecialchars($p['nombre_producto']) ?></h5>
                                    
                                 
        
                                    
                                  
                                    <div class="price-section">
                                        <?php if ($tiene_descuento): ?>
                                            <span class="text-muted text-decoration-line-through me-2">
                                                $<?= number_format($precio_original, 2) ?>
                                            </span>
                                            <span class="fw-bold text-danger">
                                                $<?= number_format($precio_descuento, 2) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="fw-bold">
                                                $<?= number_format($precio_original, 2) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    
                                    <small class="text-muted d-block mt-2">
                                        Stock: <?= $p['stock_producto'] ?> unidades
                                    </small>
                                </div>
                            </div>
                            
                         
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <?php if ($p['status_producto']): ?>
                                        <button class="btn btn-outline-dark mt-auto w-100 mb-2" onclick="addToCart(<?= $p['id_producto'] ?>)">
                                            <i class="bi bi-cart-plus me-1"></i> Agregar al carrito
                                        </button>
                                        <button class="btn btn-outline-dark mt-auto w-100 mb-2" onclick="addToCart(<?= $p['id_producto'] ?>)">
                                            <i class="bi bi-cart-plus me-1"></i> Ver descripción
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary mt-auto w-100 mb-2" disabled>
                                            No disponible
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                <?php endforeach; ?>
                
            </div>
            
        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                Aún no hay productos disponibles.
            </div>
        <?php endif; ?>
        
    </div>
</section>

<script>
function addToCart(productId) {
    // Aquí puedes agregar la funcionalidad del carrito
    alert('Producto agregado al carrito (ID: ' + productId + ')');
    // TODO: Implementar lógica del carrito
}
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>