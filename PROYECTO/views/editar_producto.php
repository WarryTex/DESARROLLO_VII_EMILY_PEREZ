<?php ob_start(); ?>

<div class="editar_producto">
    <h2>Editar Producto</h2>

    <form action="<?= BASE_URL ?>/index.php?action=edit&id=<?= $producto['id_producto'] ?>" method="post" enctype="multipart/form-data">
    
        <label for="categoria">Categoría:</label><br>
        <select id="categoria" name="id_categoria" required>
            <option value="">-- Seleccione --</option>
            <option value="1" <?= $producto['id_categoria'] == 1 ? 'selected' : '' ?>>Niña</option>
            <option value="2" <?= $producto['id_categoria'] == 2 ? 'selected' : '' ?>>Niño</option>
        </select><br><br>

        <label>Nombre del producto</label><br>
        <input type="text" name="nombre_producto" value="<?= htmlspecialchars($producto['nombre_producto']) ?>" required><br><br>

        <label>Descripción</label><br>
        <textarea name="descripcion_producto" rows="4" cols="50"><?= htmlspecialchars($producto['descripcion_producto']) ?></textarea><br><br>

        <label>Precio</label><br>
        <input type="number" step="0.01" name="precio_producto" value="<?= htmlspecialchars($producto['precio_producto']) ?>" required><br><br>

        <label>Imagen actual:</label><br>
        <?php if ($producto['imagen_producto']): ?>
            <img src="<?= BASE_URL . '/public/assets/img/' . htmlspecialchars($producto['imagen_producto']) ?>" width="150" style="display:block; margin:10px 0;"><br>
        <?php else: ?>
            <p>No hay imagen</p>
        <?php endif; ?>
        
        <label>Cambiar imagen del producto</label><br>
        <input type="file" name="imagen_producto" accept="image/*"><br>
        <small>Deja vacío si no deseas cambiar la imagen</small><br><br>

        <label>Cantidad en stock</label><br>
        <input type="number" name="stock_producto" min="0" value="<?= htmlspecialchars($producto['stock_producto']) ?>"><br><br>

        <label>Personalizable</label><br>
        <select name="personalizable">
            <option value="1" <?= $producto['personalizable'] == 1 ? 'selected' : '' ?>>Sí</option>
            <option value="0" <?= $producto['personalizable'] == 0 ? 'selected' : '' ?>>No</option>
        </select><br><br>

        <label>Dimensiones (ej: 30x40 cm)</label><br>
        <input type="text" name="dimensiones" value="<?= htmlspecialchars($producto['dimensiones']) ?>"><br><br>

        <label>Estado</label><br>
        <select name="status_producto">
            <option value="1" <?= $producto['status_producto'] == 1 ? 'selected' : '' ?>>Disponible</option>
            <option value="0" <?= $producto['status_producto'] == 0 ? 'selected' : '' ?>>No disponible</option>
        </select><br><br>

        <button type="submit">Actualizar Producto</button>
    </form>

    <br>
    <a href="<?= BASE_URL ?>/index.php?action=list">← Volver a la lista</a>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layoutadmin.php';  
?>