<?php
function obtenerLibros() {
    return [
        [
            'titulo' => 'El Quijote',
            'autor' => 'Miguel de Cervantes',
            'anio_publicacion' => 1605,
            'genero' => 'Novela',
            'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.'
        ],
        [
            'titulo' => 'Cien Años de Soledad',
            'autor' => 'Gabriel García Márquez',
            'anio_publicacion' => 1967,
            'genero' => 'Realismo Mágico',
            'descripcion' => 'La obra maestra que narra la historia de la familia Buendía en Macondo.'
        ],
        [
            'titulo' => '1984',
            'autor' => 'George Orwell',
            'anio_publicacion' => 1949,
            'genero' => 'Distopía',
            'descripcion' => 'Una crítica al totalitarismo y una visión sombría del futuro.'
        ],
        [
            'titulo' => 'Orgullo y Prejuicio',
            'autor' => 'Jane Austen',
            'anio_publicacion' => 1813,
            'genero' => 'Romántica',
            'descripcion' => 'Una historia de amor y clases sociales en la Inglaterra del siglo XIX.'
        ],
        [
            'titulo' => 'El Principito',
            'autor' => 'Antoine de Saint-Exupéry',
            'anio_publicacion' => 1943,
            'genero' => 'Fábula',
            'descripcion' => 'Un relato poético y filosófico sobre un pequeño príncipe que viaja por distintos planetas.'
        ]
    ];
}

function mostrarDetallesLibro($libro) {
    return "
        <div class='libro'>
            <h2>{$libro['titulo']}</h2>
            <p><strong>Autor:</strong> {$libro['autor']}</p>
            <p><strong>Año de publicación:</strong> {$libro['anio_publicacion']}</p>
            <p><strong>Género:</strong> {$libro['genero']}</p>
            <p>{$libro['descripcion']}</p>
        </div>
    ";
}
?>
