<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tami Tejidos</title>

    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.ico" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/styles.css">
    
</head>
<body>
    <!-- Navbar Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #6b21a8, #9333ea);">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#!">
                <i class="bi bi-shield-lock-fill me-2"></i>
                Tami Tejidos - Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= BASE_URL ?>/admin/index.php">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-box-seam me-1"></i>Productos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/index.php?action=productos">
                                <i class="bi bi-list-ul me-2"></i>Ver todos
                            </a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/index.php?action=crear_producto">
                                <i class="bi bi-plus-circle me-2"></i>Crear producto
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/index.php?action=pedidos">
                            <i class="bi bi-cart-check me-1"></i>Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/index.php?action=usuarios">
                            <i class="bi bi-people me-1"></i>Usuarios
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['usuario'])): ?>
                    <span class="me-3 text-white">
                        <i class="bi bi-person-circle me-1"></i>
                        Admin: <?= $_SESSION['usuario'] ?>
                    </span>
                    <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-light btn-sm me-2">
                        <i class="bi bi-house me-1"></i>Ver tienda
                    </a>
                    <a href="<?= BASE_URL ?>/admin/index.php?action=logout" class="btn btn-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Salir
                    </a>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>/admin/index.php?action=login" class="btn btn-light btn-sm">Iniciar Sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Admin -->
    <header class="bg-light py-4 border-bottom">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0" style="color: #6b21a8;">
                        <i class="bi bi-gear-fill me-2"></i>Panel de Administración
                    </h1>
                    <p class="text-muted mb-0 small">Gestiona tu tienda online</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/admin/index.php?action=crear_producto" class="btn btn-primary" style="background: linear-gradient(135deg, #6b21a8, #9333ea); border: none;">
                        <i class="bi bi-plus-lg me-2"></i>Nuevo Producto
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-fluid px-4">
            <?php echo $content; ?>
        </div>
    </main>

    <!-- Footer Admin -->
    <footer class="py-4 mt-5" style="background-color: #6b21a8 !important;">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="m-0 text-white small">Copyright © Tami Tejidos Admin 2025</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="m-0 text-white small">
                        <i class="bi bi-shield-check me-1"></i>Panel de Administración
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/assets/js/scripts.js"></script>

    <!-- Script para confirmación de eliminación -->
    <script>
        function confirmarEliminacion(id, nombre) {
            if (confirm('¿Estás seguro de que deseas eliminar el producto "' + nombre + '"?')) {
                window.location.href = '<?= BASE_URL ?>/admin/index.php?action=eliminar_producto&id=' + id;
            }
        }
    </script>
</body>
</html>