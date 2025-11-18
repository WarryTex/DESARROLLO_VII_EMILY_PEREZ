<?php
function crearEstudiantesDePrueba(): Estudiantes
{
    $estu = Estudiantes();

    $cargar = [
        [1, 'Emily Pérez', 'Ingeniería de Sistemas', ['Programación' => 95, 'Graficos' => 80, ]],
        [2, 'Victor Pérez', 'Ingeniería de Sistemas', ['Programación' => 0, 'Graficos' => 58, ]],
        [3, 'Victoria Pérez', 'Ingeniería de Sistemas', ['Programación' => 75, 'Graficos' => 88, ]],
        [4, 'estu25', 'Ingeniería de Sistemas', ['Programación' => 45, 'Graficos' => 100, ]],
    ];

    foreach ($cargar as [$id, $n, $c, $m]) {
        $e = new Estudiantes($id, $n, $edad, $c, $m);
        $estu->agregarEstudiante($e);
    }

    return $estu;
}

$sistema = crearEstudiantesDePrueba();


$path = __DIR__ . '/estudiantes_guardados.json';
$sistema->guardarEnJson($path);
echo "Datos guardados en: $path\n";

?>
