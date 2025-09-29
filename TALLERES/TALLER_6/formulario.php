<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro Avanzado</title>
</head>
<body>
    <h2>Formulario de Registro Avanzado</h2>
    <form action="procesar.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required><br><br>


        <label for="fechanacimiento">Fecha Nacimiento:</label>
        <input type="date" id="fechanacimiento" name="fechanacimiento" value="<?php echo isset($_POST['fechanacimiento']) ? $_POST['fechanacimiento'] : ''; ?>"><br><br>

        <label for="sitio_web">Sitio Web:</label>
        <input type="url" id="sitio_web" name="sitio_web" value="<?php echo isset($_POST['sitio_web']) ? $_POST['sitio_web'] : ''; ?>"><br><br>

        <label for="genero">Género:</label>
        <select id="genero" name="genero">
            <option value="masculino" <?php echo isset($_POST['genero']) && $_POST['genero'] == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
            <option value="femenino" <?php echo isset($_POST['genero']) && $_POST['genero'] == 'femenino' ? 'selected' : ''; ?>>Femenino</option>
            <option value="otro" <?php echo isset($_POST['genero']) && $_POST['genero'] == 'otro' ? 'selected' : ''; ?>>Otro</option>
        </select><br><br>

        <label>Intereses:</label><br>
        <input type="checkbox" id="deportes" name="intereses[]" value="deportes" <?php echo isset($_POST['intereses']) && in_array('deportes', $_POST['intereses']) ? 'checked' : ''; ?>>
        <label for="deportes">Deportes</label><br>
        <input type="checkbox" id="musica" name="intereses[]" value="musica" <?php echo isset($_POST['intereses']) && in_array('musica', $_POST['intereses']) ? 'checked' : ''; ?>>
        <label for="musica">Música</label><br>
        <input type="checkbox" id="lectura" name="intereses[]" value="lectura" <?php echo isset($_POST['intereses']) && in_array('lectura', $_POST['intereses']) ? 'checked' : ''; ?>>
        <label for="lectura">Lectura</label><br><br>

        <label for="comentarios">Comentarios:</label><br>
        <textarea id="comentarios" name="comentarios" rows="4" cols="50"><?php echo isset($_POST['comentarios']) ? $_POST['comentarios'] : ''; ?></textarea><br><br>

        <label for="foto_perfil">Foto de Perfil:</label>
        <input type="file" id="foto_perfil" name="foto_perfil"><br><br>

        <input type="submit" value="Enviar">
        <input type="reset" value="Limpiar">
    </form>
</body>
</html>
