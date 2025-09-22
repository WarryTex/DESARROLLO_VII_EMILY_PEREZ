<?php

// Autor: Emily Pérez
// Fecha: 2025-09-22

declare(strict_types=1);

class Estudiante
{
    private int $id;
    private string $nombre;
    private int $edad;
    private string $carrera;

    private array $flags = [];

    public function __construct(int $id, string $nombre, int $edad, string $carrera, array $materias = [])
    {
        $this->id = $id;
        $this->setNombre($nombre);
        $this->setEdad($edad);
        $this->setCarrera($carrera);
        $this->materias = [];

      
        foreach ($materias as $m => $cal) {
            $this->agregarMateria((string)$m, (float)$cal);
        }

        $this->evaluarFlags();
    }

   
    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getEdad(): int { return $this->edad; }
    public function getCarrera(): string { return $this->carrera; }
    public function getMaterias(): array { return $this->materias; }
    public function getFlags(): array { return $this->flags; }

    public function setNombre(string $n): void
    {
        $n = trim($n);
        if ($n === '') throw new InvalidArgumentException('Nombre no puede estar vacío');
        $this->nombre = $n;
    }

    public function setEdad(int $e): void
    {
        if ($e < 10 || $e > 120) throw new InvalidArgumentException('Edad fuera de rango');
        $this->edad = $e;
    }

    public function setCarrera(string $c): void
    {
        $c = trim($c);
        if ($c === '') throw new InvalidArgumentException('Carrera no puede estar vacía');
        $this->carrera = $c;
    }

    public function agregarMateria(string $materia, float $calificacion): void
    {
        $materia = trim($materia);
        if ($materia === '') throw new InvalidArgumentException('Nombre de materia vacío');
        if ($calificacion < 0 || $calificacion > 100) throw new InvalidArgumentException('Calificación debe estar entre 0 y 100');
        $this->materias[$materia] = $calificacion;
        $this->evaluarFlags();
    }

    public function obtenerPromedio(): float
    {
        if (empty($this->materias)) return 0.0;
        // Usamos array_reduce
        $suma = array_reduce(array_values($this->materias), function ($carry, $item) {
            return $carry + $item;
        }, 0.0);
        return round($suma / count($this->materias), 2);
    }

    public function obtenerDetalles(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'edad' => $this->edad,
            'carrera' => $this->carrera,
            'materias' => $this->materias,
            'promedio' => $this->obtenerPromedio(),
            'flags' => $this->flags,
        ];
    }

    public function __toString(): string
    {
        $det = $this->obtenerDetalles();
        $matStr = '';
        foreach ($det['materias'] as $m => $c) {
            $matStr .= "    - $m: $c\n";
        }
        $flags = empty($this->flags) ? 'Ninguno' : implode(', ', array_keys(array_filter($this->flags)));
        return "Estudiante #{$det['id']} - {$det['nombre']} ({$det['carrera']})\nEdad: {$det['edad']}\nPromedio: {$det['promedio']}\nFlags: $flags\nMaterias:\n$matStr";
    }

    private function evaluarFlags(): void
    {
        $prom = $this->obtenerPromedio();
        $reprobadas = array_filter($this->materias, fn($cal) => $cal < 60);

        // Honor Roll si promedio >= 90 y no tiene reprobadas
        $this->flags['honor_roll'] = ($prom >= 90 && count($reprobadas) === 0);
        // En riesgo académico si promedio < 65 o tiene 2+ reprobadas
        $this->flags['en_riesgo'] = ($prom < 65 || count($reprobadas) >= 2);
        // Recuperación si tiene 1 reprobada
        $this->flags['recuperacion'] = (count($reprobadas) === 1);
    }
}



class SistemaGestionEstudiantes
{
  
    private array $estudiantes = [];
    private array $graduados = [];

  
    public function agregarEstudiante(Estudiante $estudiante): void
    {
        $id = $estudiante->getId();
        if (isset($this->estudiantes[$id])) {
            throw new InvalidArgumentException("Estudiante con ID $id ya existe en el sistema");
        }
        $this->estudiantes[$id] = $estudiante;
    }

  
    public function obtenerEstudiante(int $id): ?Estudiante
    {
        return $this->estudiantes[$id] ?? null;
    }


    public function listarEstudiantes(): array
    {
        return array_values($this->estudiantes);
    }


    public function calcularPromedioGeneral(): float
    {
        $est = $this->listarEstudiantes();
        if (empty($est)) return 0.0;
        $proms = array_map(fn(Estudiante $e) => $e->obtenerPromedio(), $est);
        $suma = array_reduce($proms, fn($c, $i) => $c + $i, 0.0);
        return round($suma / count($proms), 2);
    }

    public function obtenerEstudiantesPorCarrera(string $carrera): array
    {
        $carrera = mb_strtolower(trim($carrera));
        return array_values(array_filter($this->estudiantes, function (Estudiante $e) use ($carrera) {
            return mb_stripos($e->getCarrera(), $carrera) !== false;
        }));
    }


    public function obtenerMejorEstudiante(): ?Estudiante
    {
        $est = $this->listarEstudiantes();
        if (empty($est)) return null;
        usort($est, fn(Estudiante $a, Estudiante $b) => $b->obtenerPromedio() <=> $a->obtenerPromedio());
        return $est[0];
    }

   
    public function generarReporteRendimiento(): array
    {
        $mapaMaterias = [];

        foreach ($this->estudiantes as $est) {
            foreach ($est->getMaterias() as $m => $cal) {
                $mapaMaterias[$m][] = $cal;
            }
        }

        $reporte = [];
        foreach ($mapaMaterias as $m => $calificaciones) {
            $prom = round(array_sum($calificaciones) / count($calificaciones), 2);
            $reporte[$m] = [
                'promedio' => $prom,
                'max' => max($calificaciones),
                'min' => min($calificaciones),
                'cantidad' => count($calificaciones),
            ];
        }

        return $reporte;
    }


    public function graduarEstudiante(int $id): bool
    {
        if (!isset($this->estudiantes[$id])) return false;
        $this->graduados[$id] = $this->estudiantes[$id];
        unset($this->estudiantes[$id]);
        return true;
    }

  
    public function generarRanking(): array
    {
        $est = $this->listarEstudiantes();
        usort($est, fn(Estudiante $a, Estudiante $b) => $b->obtenerPromedio() <=> $a->obtenerPromedio());
        return $est;
    }

   
    public function buscar(string $query): array
    {
        $q = mb_strtolower(trim($query));
        return array_values(array_filter($this->estudiantes, function (Estudiante $e) use ($q) {
            return mb_stripos($e->getNombre(), $q) !== false || mb_stripos($e->getCarrera(), $q) !== false;
        }));
    }

    public function estadisticasPorCarrera(): array
    {
        $porCarrera = [];
        foreach ($this->estudiantes as $e) {
            $c = $e->getCarrera();
            $porCarrera[$c][] = $e;
        }

        $stats = [];
        foreach ($porCarrera as $c => $lista) {
            $proms = array_map(fn(Estudiante $s) => $s->obtenerPromedio(), $lista);
            $mejor = $lista[array_keys($proms, max($proms))[0]];
            $stats[$c] = [
                'numero_estudiantes' => count($lista),
                'promedio_general' => round(array_sum($proms) / count($proms), 2),
                'mejor_estudiante' => $mejor->obtenerDetalles(),
            ];
        }

        return $stats;
    }

    public function guardarEnJson(string $path): void
    {
        $data = [
            'estudiantes' => array_map(fn(Estudiante $e) => $e->obtenerDetalles(), $this->listarEstudiantes()),
            'graduados' => array_map(fn(Estudiante $e) => $e->obtenerDetalles(), array_values($this->graduados)),
        ];
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function cargarDesdeJson(string $path): void
    {
        if (!file_exists($path)) throw new RuntimeException('Archivo no encontrado: ' . $path);
        $raw = json_decode(file_get_contents($path), true);
        if (!is_array($raw) || !isset($raw['estudiantes'])) throw new RuntimeException('Formato JSON inválido');

    
        $this->estudiantes = [];
        $this->graduados = [];

        foreach ($raw['estudiantes'] as $d) {
            $e = new Estudiante($d['id'], $d['nombre'], (int)$d['edad'], $d['carrera'], $d['materias'] ?? []);
            $this->estudiantes[$e->getId()] = $e;
        }

        if (isset($raw['graduados'])) {
            foreach ($raw['graduados'] as $d) {
                $g = new Estudiante($d['id'], $d['nombre'], (int)$d['edad'], $d['carrera'], $d['materias'] ?? []);
                $this->graduados[$g->getId()] = $g;
            }
        }
    }
}



function crearEstudiantesDePrueba(): SistemaGestionEstudiantes
{
    $sis = new SistemaGestionEstudiantes();

    $datos = [
        [1, 'Ana Pérez', 20, 'Ingeniería de Sistemas', ['Programación' => 95, 'Matemáticas' => 88, 'Física' => 78]],
        [2, 'Luis Gómez', 22, 'Administración', ['Contabilidad' => 85, 'Economía' => 79, 'Estadística' => 82]],
        [3, 'María Ruiz', 19, 'Medicina', ['Biología' => 92, 'Química' => 90, 'Anatomía' => 94]],
        [4, 'Carlos Díaz', 21, 'Ingeniería Civil', ['Mecánica' => 68, 'Topografía' => 72, 'Materiales' => 60]],
        [5, 'Sofía Herrera', 23, 'Derecho', ['Constitucional' => 88, 'Civil' => 91, 'Penal' => 87]],
        [6, 'Jorge Morales', 20, 'Ingeniería de Sistemas', ['Programación' => 55, 'Matemáticas' => 60, 'Redes' => 58]],
        [7, 'Luisa Fernández', 18, 'Economía', ['Microeconomía' => 77, 'Macroeconomía' => 80, 'Econometría' => 65]],
        [8, 'Patricia Castro', 24, 'Psicología', ['Teorías' => 70, 'Evaluación' => 75, 'Desarrollo' => 68]],
        [9, 'Ricardo Pérez', 25, 'Ingeniería de Sistemas', ['Programación' => 99, 'Algoritmos' => 96, 'Bases de Datos' => 94]],
        [10, 'Daniela López', 22, 'Administración', ['Marketing' => 82, 'Gestión' => 78, 'Finanzas' => 74]],
        [11, 'Andrés Molina', 23, 'Medicina', ['Biología' => 58, 'Química' => 62, 'Ética' => 60]],
    ];

    foreach ($datos as [$id, $n, $edad, $c, $m]) {
        $e = new Estudiante($id, $n, $edad, $c, $m);
        $sis->agregarEstudiante($e);
    }

    return $sis;
}

// Ejecutar pruebas y mostrar resultados
$sistema = crearEstudiantesDePrueba();

echo "=== Lista de Estudiantes ===\n";
foreach ($sistema->listarEstudiantes() as $e) {
    echo $e->__toString() . "\n";
}

echo "=== Promedio General del Sistema ===\n";
echo $sistema->calcularPromedioGeneral() . "\n\n";

echo "=== Mejor Estudiante ===\n";
$mejor = $sistema->obtenerMejorEstudiante();
if ($mejor) echo $mejor->__toString() . "\n";

echo "=== Reporte de Rendimiento por Materia ===\n";
$reporte = $sistema->generarReporteRendimiento();
foreach ($reporte as $mat => $info) {
    echo "{$mat}: promedio={$info['promedio']}, max={$info['max']}, min={$info['min']}, cantidad={$info['cantidad']}\n";
}

echo "\n=== Ranking ===\n";
$rank = $sistema->generarRanking();
$pos = 1;
foreach ($rank as $r) {
    echo "#{$pos} - {$r->getNombre()} (Prom: {$r->obtenerPromedio()})\n";
    $pos++;
}

echo "\n=== Búsquedas (" . 'ing' . ") ===\n"; // ejemplo parcial
$busq = $sistema->buscar('ing');
foreach ($busq as $b) echo "- {$b->getNombre()} ({$b->getCarrera()})\n";

echo "\n=== Estadísticas por Carrera ===\n";
$estats = $sistema->estadisticasPorCarrera();
foreach ($estats as $c => $info) {
    echo "Carrera: $c - #Estudiantes: {$info['numero_estudiantes']} - Promedio: {$info['promedio_general']} - Mejor: {$info['mejor_estudiante']['nombre']}\n";
}


$sistema->graduarEstudiante(3);
echo "\nEstudiante con ID 3 graduado.\n";

$path = __DIR__ . '/estudiantes_guardados.json';
$sistema->guardarEnJson($path);
echo "Datos guardados en: $path\n";


