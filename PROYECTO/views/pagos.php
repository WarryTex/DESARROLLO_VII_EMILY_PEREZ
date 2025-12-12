<?php ob_start(); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Realizar Pago</h2>
                    
                    <div class="alert alert-info">
                        <strong>Total a pagar:</strong> $<?= number_format($total, 2) ?>
                    </div>
                    
                    <form method="POST" action="<?= BASE_URL ?>/index.php?action=confirmar_pago">
                        <h5>Información de pago</h5>
                        
                        <label>Número de tarjeta</label>
                        <input type="text" name="numero_tarjeta" class="form-control mb-3" placeholder="1234 5678 9012 3456" required>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label>Fecha de vencimiento</label>
                                <input type="text" name="vencimiento" class="form-control mb-3" placeholder="MM/AA" required>
                            </div>
                            <div class="col-md-6">
                                <label>CVV</label>
                                <input type="text" name="cvv" class="form-control mb-3" placeholder="123" required>
                            </div>
                        </div>
                        
                        <label>Nombre del titular</label>
                        <input type="text" name="titular" class="form-control mb-3" required>
                        
                        <input type="hidden" name="total" value="<?= $total ?>">
                        
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Pagar $<?= number_format($total, 2) ?>
                        </button>
                        
                        <a href="<?= BASE_URL ?>/index.php?action=carrito" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>