<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

// Función para calcular la edad basada en la fecha de nacimiento
function calcularEdad($fechaNacimiento) {
    $fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
    $edad = $fecha ? (new DateTime())->diff($fecha)->y : 0;
    return $edad;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];

    // Campos a procesar
    $campos = ['nombre', 'email', 'fechanacimiento', 'sitio_web', 'genero', 'intereses', 'comentarios'];

    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $_POST[$campo];

            // Construir nombres de funciones dinámicamente en CamelCase
            $funcSanitizar = "sanitizar" . ucfirst($campo);
            $funcValidar = "validar" . ucfirst($campo);

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

    // Calcular la edad automáticamente
    if (isset($_POST['fechanacimiento'])) {
        $edadCalculada = calcularEdad($_POST['fechanacimiento']);
        $datos['edad'] = $edadCalculada; // Guardamos la edad calculada
    }

    // Procesar la foto de perfil de manera segura
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        $carpetaUploads = __DIR__ . '/uploads/'; // Ruta absoluta a la carpeta uploads

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

    // Si hay errores, los mostramos
    if (!empty($errores)) {
        echo "<ul>";
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {
        // Si no hay errores, mostramos los datos recibidos
        echo "<h2>Datos Recibidos:</h2>";
        echo "<table border='1'>";
        foreach ($datos as $campo => $valor) {
            echo "<tr>";
            echo "<th>" . ucfirst($campo) . "</th>";
            if ($campo === 'intereses') {
                echo "<td>" . implode(", ", $valor) . "</td>";
            } elseif ($campo === 'foto_perfil') {
                echo "<td><img src='$valor' width='100'></td>";
            } else {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        // Guardar los datos en un archivo JSON
        $archivoDatos = 'registros.json';

        // Si el archivo no existe, creamos uno vacío
        if (!file_exists($archivoDatos)) {
            file_put_contents($archivoDatos, json_encode([]));
        }

        // Leer el archivo actual y agregar los nuevos datos
        $registros = json_decode(file_get_contents($archivoDatos), true);
        $registros[] = $datos; // Añadimos el nuevo registro

        // Guardamos los registros actualizados
        file_put_contents($archivoDatos, json_encode($registros, JSON_PRETTY_PRINT));
    }
}
?>
