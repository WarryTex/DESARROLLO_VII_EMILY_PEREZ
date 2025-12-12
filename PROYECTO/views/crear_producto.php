<?php ob_start(); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 my-5">
                <div class="card-header bg-gradient text-black text-center py-4 rounded-top-4">
                    <h2 class="mb-0 fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Producto
                    </h2>
                </div>
                
                <div class="card-body p-5">
                    <?php if (isset($_GET['msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>¡Éxito!</strong> Producto creado correctamente.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/index.php?action=create" method="post" enctype="multipart/form-data">
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="categoria" class="form-label fw-semibold">
                                    <i class="bi bi-tag-fill text-primary me-1"></i>Categoría
                                </label>
                                <select id="categoria" name="id_categoria" class="form-select form-select-lg" required>
                                    <option value="">-- Seleccione una categoría --</option>
                                    <option value="1">👧 Para Niña</option>
                                    <option value="2">👦 Para Niño</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="precio" class="form-label fw-semibold">
                                    <i class="bi bi-currency-dollar text-success me-1"></i>Precio
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="precio_producto" class="form-control" id="precio" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-semibold">
                                <i class="bi bi-box-seam text-info me-1"></i>Nombre del Producto
                            </label>
                            <input type="text" name="nombre_producto" id="nombre" class="form-control form-control-lg" 
                                   placeholder="Ej: Portadocumentos Rosa con Flores" required>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-semibold">
                                <i class="bi bi-text-paragraph text-secondary me-1"></i>Descripción
                            </label>
                            <textarea name="descripcion_producto" id="descripcion" rows="4" 
                                      class="form-control form-control-lg" 
                                      placeholder="Describe las características del producto..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="imagen" class="form-label fw-semibold">
                                <i class="bi bi-image text-warning me-1"></i>Imagen del Producto
                            </label>
                            <input type="file" name="imagen_producto" id="imagen" 
                                   class="form-control form-control-lg" accept="image/*">
                            <div class="form-text">Formatos: JPG, PNG, GIF. Tamaño máximo: 5MB</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="stock" class="form-label fw-semibold">
                                    <i class="bi bi-boxes text-danger me-1"></i>Stock
                                </label>
                                <input type="number" name="stock_producto" id="stock" min="0" value="0" 
                                       class="form-control form-control-lg">
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="personalizable" class="form-label fw-semibold">
                                    <i class="bi bi-pencil-square text-purple me-1"></i>Personalizable
                                </label>
                                <select name="personalizable" id="personalizable" class="form-select form-select-lg">
                                    <option value="1">✓ Sí</option>
                                    <option value="0" selected>✗ No</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on text-success me-1"></i>Estado
                                </label>
                                <select name="status_producto" id="status" class="form-select form-select-lg">
                                    <option value="1" selected>🟢 Disponible</option>
                                    <option value="0">🔴 No disponible</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="dimensiones" class="form-label fw-semibold">
                                <i class="bi bi-rulers text-dark me-1"></i>Dimensiones
                            </label>
                            <input type="text" name="dimensiones" id="dimensiones" 
                                   class="form-control form-control-lg" 
                                   placeholder="Ej: 30x40 cm">
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-lg btn-primary py-3 fw-bold">
                                <i class="bi bi-save me-2"></i>Guardar Producto
                            </button>
                            <a href="<?= BASE_URL ?>/index.php?action=list" 
                               class="btn btn-lg btn-outline-secondary py-3">
                                <i class="bi bi-arrow-left me-2"></i>Volver a la Lista
                            </a>
                        </div>
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