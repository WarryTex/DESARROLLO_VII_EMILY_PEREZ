 <!DOCTYPE html>
<html lang="es">
<head>
    <!-- Layout esta compuesto por un template de booptraps 5, una interfaz sencilla de ecommerce. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce-TamiTejidos</title>

    <!--Favicon.ico es utilizado por ser un icono predeterminado como logo en el navegador de nuestra página Ecommerce-->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.ico" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/styles.css">
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">Tami Tejidos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?action=pedidos">Pedidos</a></li>
                    <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Productos</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?action=productos">Todos los productos</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?action=productos&categoria=1">Para niñas</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?action=productos&categoria=2">Para niños</a></li>
                            </ul>
                        </li>
                </ul>
                <div class="d-flex align-items-center">

    <?php if (isset($_SESSION['usuario'])): ?>
    <span class="me-2">Hola, <?= $_SESSION['usuario'] ?></span>
    <a href="<?= BASE_URL ?>/index.php?action=logout" class="btn btn-outline-dark me-2">Salir</a>
    <?php else: ?>
    <a href="<?= BASE_URL ?>/index.php?action=login" class="btn btn-outline-dark me-2">Iniciar Sesión</a>
    <?php endif; ?>
    
    <a href="<?= BASE_URL ?>/index.php?action=carrito" class="btn btn-outline-dark position-relative">
    <i class="bi-cart-fill me-1"></i>
    Carrito
    <span class="badge bg-dark text-white ms-1 rounded-pill"><?= Carrito::contar() ?></span>
</a>
</div>
        </div>
    </nav>

    <header class="hero-section position-relative overflow-hidden" 
            style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.65), rgba(236, 72, 153, 0.65)), 
                   url('<?php echo BASE_URL; ?>/public/assets/img/bebe.jpg') no-repeat center center/cover; 
                   min-height: 550px; 
                   display: flex; 
                   align-items: center; 
                   justify-content: center;">
        
        <div class="particles">
            <span style="top:15%; left:10%; animation-delay:0s;"></span>
            <span style="top:40%; left:75%; animation-delay:3s;"></span>
            <span style="top:60%; left:20%; animation-delay:6s;"></span>
            <span style="top:30%; left:60%; animation-delay:2s;"></span>
            <span style="top:80%; left:45%; animation-delay:5s;"></span>
            <span style="top:20%; left:85%; animation-delay:8s;"></span>
            <span style="top:70%; left:70%; animation-delay:1s;"></span>
        </div>

        <div class="container px-4 px-lg-5 position-relative z-10">
            <div class="text-center text-white">
                <h1 class="display-2 fw-bolder mb-4 animate__animated animate__fadeInDown">
                    ¡Bienvenidos!
                </h1>
                <p class="lead fw-bold mb-2 animate__animated animate__fadeInUp animate__delay-1s" 
                   style="font-size: 2rem;">
                    Portadocumentos para bebés
                </p>
                <p class="fs-5 mb-0 animate__animated animate__fadeInUp animate__delay-1s">
                    Calidad y estilo para tus documentos
                </p>
            </div>
        </div>

        <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x mb-4">
            <i class="bi bi-chevron-down text-white fs-1 animate__animated animate__bounce animate__infinite"></i>
        </div>
    </header>

    <!-- Se agregó py-5 para padding vertical superior e inferior -->
    <main class="py-5 mb-5">
        <?php echo $content; ?>
    </main>

   <footer class="py-5 mt-5" style="background-color: #6b21a8 !important;">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright © Tami Tejidos 2025</p>
    </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="<?php echo BASE_URL; ?>/public/assets/js/scripts.js"></script>
</body>
</html>