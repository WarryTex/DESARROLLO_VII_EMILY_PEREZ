<?php
function validarNombre($nombre) {
    return !empty($nombre) && strlen($nombre) <= 50;
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validarEdad($edad) {
    return is_numeric($edad) && $edad >= 18 && $edad <= 120;
}

function validarFechaNacimiento($fechaNacimiento) {
    $fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
    $edad = $fecha ? (new DateTime())->diff($fecha)->y : 0;
    return $edad >= 18;
}

function validarSitioWeb($sitioWeb) {
    return empty($sitioWeb) || filter_var($sitioWeb, FILTER_VALIDATE_URL);
}

function validarGenero($genero) {
    $generosValidos = ['masculino', 'femenino', 'otro'];
    return in_array($genero, $generosValidos);
}

function validarIntereses($intereses) {
    $interesesValidos = ['deportes', 'musica', 'lectura'];
    return !empty($intereses) && count(array_intersect($intereses, $interesesValidos)) === count($intereses);
}

function validarComentarios($comentarios) {
    return strlen($comentarios) <= 500;
}

function validarFotoPerfil($archivo) {
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
    $tamanoMaximo = 1 * 1024 * 1024; // 1MB

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    if (!in_array($archivo['type'], $tiposPermitidos)) {
        return false;
    }

    if ($archivo['size'] > $tamanoMaximo) {
        return false;
    }

    // Generar un nombre único para evitar sobrescribir archivos existentes
    $carpetaUploads = __DIR__ . '/uploads/';
    $nombreArchivo = basename($archivo['name']);
    $rutaDestino = $carpetaUploads . $nombreArchivo;

    // Si el archivo ya existe, generar un nombre único
    if (file_exists($rutaDestino)) {
        $nombreArchivo = pathinfo($archivo['name'], PATHINFO_FILENAME); // Nombre del archivo sin extensión
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivoUnico = $nombreArchivo . '_' . time() . '.' . $extension; // Añadir timestamp al nombre
        $rutaDestino = $carpetaUploads . $nombreArchivoUnico;
    }

    // Guardar la ruta destino única
    $archivo['name'] = $nombreArchivoUnico;

    return true;  // Si pasa todas las validaciones
}
?>
