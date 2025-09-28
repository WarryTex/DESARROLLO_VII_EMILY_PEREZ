<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

// Función para convertir nombres con guion bajo a CamelCase
function convertirACamelCase($texto) {
    $texto = str_replace('_', ' ', $texto);
    $texto = ucwords($texto); // Primera letra de cada palabra en mayúscula
    return str_replace(' ', '', $texto);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];

    // Campos a procesar
    $campos = ['nombre', 'email', 'edad', 'sitio_web', 'genero', 'intereses', 'comentarios'];

    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $_POST[$campo];

            // Construir nombres de funciones dinámicamente en CamelCase
            $funcSanitizar = "sanitizar" . convertirACamelCase($campo);
            $funcValidar = "validar" . convertirACamelCase($campo);

            // Sanitizar
            if (function_exists($funcSanitizar)) {
                $valorSanitizado = call_user_func($funcSanitizar, $valor);
            } else {
                $valorSanitizado = $valor;
            }

            // Validar
            if (function_exists($funcValidar)) {
                if (!call_user_func($funcValidar, $valorSanitizado)) {
                    $errores[] = "El campo $campo no es válido.";
                }
            }

            $datos[$campo] = $valorSanitizado;
        }
    }

    // Procesar la foto de perfil de manera segura
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {

        $carpetaUploads = __DIR__ . '/uploads/'; // Ruta absoluta a la carpeta uploads

        // Crear la carpeta uploads si no existe
        if (!is_dir($carpetaUploads)) {
            mkdir($carpetaUploads, 0755, true);
        }

        $archivoTmp = $_FILES['foto_perfil']['tmp_name'];
        $nombreArchivo = basename($_FILES['foto_perfil']['name']);
        $rutaDestino = $carpetaUploads . $nombreArchivo;

        // Validar que sea un archivo subido
        if (!is_uploaded_file($archivoTmp)) {
            $errores[] = "No se encontró el archivo temporal de la foto.";
        } else {
            // Validar extensión de imagen
            $ext = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
            $extPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($ext, $extPermitidas)) {
                $errores[] = "Solo se permiten imágenes con extensión JPG, JPEG, PNG o GIF.";
            } 
            // Validar tamaño (máximo 2MB)
            elseif ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) {
                $errores[] = "La imagen no puede superar los 2MB.";
            } 
            else {
                // Mover archivo a la carpeta uploads
                if (move_uploaded_file($archivoTmp, $rutaDestino)) {
                    $datos['foto_perfil'] = 'uploads/' . $nombreArchivo; // Ruta relativa para mostrar
                } else {
                    $errores[] = "Hubo un error al subir la foto de perfil.";
                }
            }
        }
    }

    // Mostrar resultados o errores
    if (empty($errores)) {
        echo "<h2>Datos Recibidos:</h2>";
        foreach ($datos as $campo => $valor) {
            if ($campo === 'intereses') {
                echo "$campo: " . implode(", ", $valor) . "<br>";
            } elseif ($campo === 'foto_perfil') {
                echo "$campo: <img src='$valor' width='100'><br>";
            } else {
                echo "$campo: $valor<br>";
            }
        }
    } else {
        echo "<h2>Errores:</h2>";
        foreach ($errores as $error) {
            echo "$error<br>";
        }
    }
} else {
    echo "Acceso no permitido.";
}
?>


