<?php ob_start(); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Registro</h2>
                    
                    <form method="POST">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre_completo" class="form-control mb-3" required>
                        
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control mb-3" required>
                        
                        <label>Email</label>
                        <input type="email" name="email" class="form-control mb-3" required>
                        
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control mb-3" required>
                        
                        <label>Contraseña</label>
                        <input type="password" name="contrasena" class="form-control mb-3" required>
                        
                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </form>
                    
                    <p class="text-center mt-3">¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/index.php?action=login">Inicia sesión</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>