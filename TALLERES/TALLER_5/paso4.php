<?php
// Paso 4: Ordenamiento y Filtrado Avanzado de Arreglos

// 1. Definir el arreglo de libros
$biblioteca = [
    [
        "titulo" => "Cien años de soledad",
        "autor" => "Gabriel García Márquez",
        "año" => 1967,
        "genero" => "Realismo mágico",
        "prestado" => true
    ],
    [
        "titulo" => "1984",
        "autor" => "George Orwell",
        "año" => 1949,
        "genero" => "Ciencia ficción",
        "prestado" => false
    ],
    [
        "titulo" => "El principito",
        "autor" => "Antoine de Saint-Exupéry",
        "año" => 1943,
        "genero" => "Literatura infantil",
        "prestado" => true
    ],
    [
        "titulo" => "Don Quijote de la Mancha",
        "autor" => "Miguel de Cervantes",
        "año" => 1605,
        "genero" => "Novela",
        "prestado" => false
    ],
    [
        "titulo" => "Orgullo y prejuicio",
        "autor" => "Jane Austen",
        "año" => 1813,
        "genero" => "Novela romántica",
        "prestado" => true
    ]
];

// 2. Función para imprimir la biblioteca
function imprimirBiblioteca($libros) {
    foreach ($libros as $libro) {
        echo "{$libro['titulo']} - {$libro['autor']} ({$libro['año']}) - {$libro['genero']} - " . 
             ($libro['prestado'] ? "Prestado" : "Disponible") . "\n";
    }
    echo "\n";
}

echo "Biblioteca original:\n";
imprimirBiblioteca($biblioteca);

// 3. Ordenar libros por año de publicación (del más antiguo al más reciente)
usort($biblioteca, function($a, $b) {
    return $a['año'] - $b['año'];
});

echo "Libros ordenados por año de publicación:\n";
imprimirBiblioteca($biblioteca);

// 4. Ordenar libros alfabéticamente por título
usort($biblioteca, function($a, $b) {
    return strcmp($a['titulo'], $b['titulo']);
});

echo "Libros ordenados alfabéticamente por título:\n";
imprimirBiblioteca($biblioteca);

// 5. Filtrar libros disponibles (no prestados)
$librosDisponibles = array_filter($biblioteca, function($libro) {
    return !$libro['prestado'];
});

echo "Libros disponibles:\n";
imprimirBiblioteca($librosDisponibles);

// 6. Filtrar libros por género
function filtrarPorGenero($libros, $genero) {
    return array_filter($libros, function($libro) use ($genero) {
        return strcasecmp($libro['genero'], $genero) === 0;
    });
}

$librosCienciaFiccion = filtrarPorGenero($biblioteca, "Ciencia ficción");
echo "Libros de Ciencia ficción:\n";
imprimirBiblioteca($librosCienciaFiccion);

// 7. Obtener lista de autores únicos
$autores = array_unique(array_column($biblioteca, 'autor'));
sort($autores);

echo "Lista de autores:\n";
foreach ($autores as $autor) {
    echo "- $autor\n";
}
echo "\n";

// 8. Calcular el año promedio de publicación
$añoPromedio = array_sum(array_column($biblioteca, 'año')) / count($biblioteca);
echo "Año promedio de publicación: " . round($añoPromedio, 2) . "\n\n";

// 9. Encontrar el libro más antiguo y el más reciente
$libroMasAntiguo = array_reduce($biblioteca, function($carry, $libro) {
    return (!$carry || $libro['año'] < $carry['año']) ? $libro : $carry;
});

$libroMasReciente = array_reduce($biblioteca, function($carry, $libro) {
    return (!$carry || $libro['año'] > $carry['año']) ? $libro : $carry;
});

echo "Libro más antiguo: {$libroMasAntiguo['titulo']} ({$libroMasAntiguo['año']})\n";
echo "Libro más reciente: {$libroMasReciente['titulo']} ({$libroMasReciente['año']})\n\n";

// 10. Función de búsqueda (parcial, insensible a mayúsculas)
function buscarLibros($biblioteca, $termino) {
    $termino = strtolower($termino); // convertir todo a minúsculas
    return array_filter($biblioteca, function($libro) use ($termino) {
        return strpos(strtolower($libro['titulo']), $termino) !== false ||
               strpos(strtolower($libro['autor']), $termino) !== false;
    });
}

// Ejemplo de uso de la función de búsqueda
$resultadosBusqueda = buscarLibros($biblioteca, "quijote");
echo "Resultados de búsqueda para 'quijote':\n";
imprimirBiblioteca($resultadosBusqueda);

// 11. Función que genera un reporte de la biblioteca
function generarReporteBiblioteca($biblioteca) {
    $totalLibros = count($biblioteca);

    // Número de libros prestados
    $prestados = count(array_filter($biblioteca, function($libro) {
        return $libro['prestado'];
    }));

    // Número de libros por género
    $porGenero = [];
    foreach ($biblioteca as $libro) {
        $genero = $libro['genero'];
        if (!isset($porGenero[$genero])) {
            $porGenero[$genero] = 0;
        }
        $porGenero[$genero]++;
    }

    // Autor con más libros
    $conteoAutores = [];
    foreach ($biblioteca as $libro) {
        $autor = $libro['autor'];
        if (!isset($conteoAutores[$autor])) {
            $conteoAutores[$autor] = 0;
        }
        $conteoAutores[$autor]++;
    }
    arsort($conteoAutores); // ordenar de mayor a menor
    $autorTop = array_key_first($conteoAutores);

    return [
        "total_libros" => $totalLibros,
        "prestados" => $prestados,
        "disponibles" => $totalLibros - $prestados,
        "por_genero" => $porGenero,
        "autor_mas_libros" => $autorTop
    ];
}

// Ejemplo de uso de la función de reporte
echo "\nReporte de la Biblioteca:\n";
print_r(generarReporteBiblioteca($biblioteca));
?>
