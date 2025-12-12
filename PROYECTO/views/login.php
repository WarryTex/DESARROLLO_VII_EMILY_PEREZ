<?php ob_start(); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Iniciar Sesión</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control mb-3" required>
                        
                        <label>Contraseña</label>
                        <input type="password" name="contrasena" class="form-control mb-3" required>
                        
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                    
                    <p class="text-center mt-3">¿No tienes cuenta? <a href="<?= BASE_URL ?>/index.php?action=registro">Regístrate</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';