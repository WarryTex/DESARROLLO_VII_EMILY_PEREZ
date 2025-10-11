<?php
require_once "config_mysqli.php";

// 1. Mostrar las últimas 5 publicaciones con el nombre del autor y la fecha de publicación
$sql = "SELECT p.titulo, u.nombre as autor, p.fecha_publicacion 
        FROM publicaciones p 
        INNER JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.fecha_publicacion DESC 
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h3>Últimas 5 publicaciones:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Título: " . $row['titulo'] . ", Autor: " . $row['autor'] . ", Fecha: " . $row['fecha_publicacion'] . "<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

// 2. Listar los usuarios que no han realizado ninguna publicación
$sql = "SELECT u.id, u.nombre 
        FROM usuarios u 
        LEFT JOIN publicaciones p ON u.id = p.usuario_id 
        WHERE p.id IS NULL";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h3>Usuarios sin publicaciones:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Usuario: " . $row['nombre'] . "<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

// 3. Calcular el promedio de publicaciones por usuario
$sql = "SELECT AVG(num_publicaciones) as promedio 
        FROM (SELECT u.id, COUNT(p.id) as num_publicaciones 
              FROM usuarios u 
              LEFT JOIN publicaciones p ON u.id = p.usuario_id 
              GROUP BY u.id) as subquery";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<h3>Promedio de publicaciones por usuario:</h3>";
    echo "Promedio: " . number_format($row['promedio'], 2) . "<br>";
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

// 4. Encontrar la publicación más reciente de cada usuario
$sql = "SELECT u.nombre, p.titulo, p.fecha_publicacion 
        FROM usuarios u 
        LEFT JOIN publicaciones p ON u.id = p.usuario_id 
        WHERE p.fecha_publicacion = (
            SELECT MAX(fecha_publicacion) 
            FROM publicaciones p2 
            WHERE p2.usuario_id = u.id
        ) 
        AND p.id IS NOT NULL";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h3>Publicación más reciente de cada usuario:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Usuario: " . $row['nombre'] . ", Título: " . $row['titulo'] . ", Fecha: " . $row['fecha_publicacion'] . "<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>