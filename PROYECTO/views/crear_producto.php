<?php ob_start(); ?>

<div class="crear_producto">
    <h2>Crear un producto</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color:green; font-weight:bold;">¡Producto creado con éxito!</p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/index.php?action=create" method="post" enctype="multipart/form-data">
    
        <label for="categoria">Categoría:</label><br>
        <select id="categoria" name="id_categoria" required>
            <option value="">-- Seleccione --</option>
            <option value="1">Niña</option>
            <option value="2">Niño</option>
        </select><br><br>

        <label>Nombre del producto</label><br>
        <input type="text" name="nombre_producto" required><br><br>

        <label>Descripción</label><br>
        <textarea name="descripcion_producto" rows="4" cols="50"></textarea><br><br>

        <label>Precio</label><br>
        <input type="number" step="0.01" name="precio_producto" required><br><br>

        <label>Imagen del producto</label><br>
        <input type="file" name="imagen_producto" accept="image/*"><br><br>

        <label>Cantidad en stock</label><br>
        <input type="number" name="stock_producto" min="0" value="0"><br><br>

        <label>Personalizable</label><br>
        <select name="personalizable">
            <option value="1">Sí</option>
            <option value="0" selected>No</option>
        </select><br><br>

        <label>Dimensiones (ej: 30x40 cm)</label><br>
        <input type="text" name="dimensiones"><br><br>

        <label>Estado</label><br>
        <select name="status_producto">
            <option value="1" selected>Disponible</option>
            <option value="0">No disponible</option>
        </select><br><br>

        <button type="submit">Guardar Producto</button>
    </form>

    <br>
    <a href="<?= BASE_URL ?>/index.php?action=list">← Volver a la lista</a>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
