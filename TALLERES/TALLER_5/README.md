Proyecto Final: Sistema de Gestión de Estudiante

El proyecto se encuentra guardado en el archivo php (proyecto_final.php) 

El resultado muestra: 
   Al ejecutar el script, se mostrarán:

   * Lista completa de estudiantes.
   * Promedio general del sistema.
   * Mejor estudiante.
   * Reporte de rendimiento por materia.
   * Ranking de estudiantes por promedio.
   * Resultados de búsquedas.
   * Estadísticas por carrera.
   * Ejemplo de graduación de un estudiante.
   * Confirmación de guardado de datos en JSON.

El sistema genera el archivo `estudiantes_guardados.json` con los datos de estudiantes y graduados.

Funcionalidades

1.Clase "Estudiante"

Atributos:id, nombre, edad, carrera, materias (con calificaciones)
Métodos
-agregarMateria($materia, $calificacion)
-obtenerPromedio()
-obtenerDetalles()
-__toString()

2.Clase SistemaGestionEstudiantes

Gestión: agregar, listar, buscar, graduar estudiantes.
Reportes: promedio general, ranking, mejor estudiante.
Rendimiento académico:reporte por materia (promedio, máximo, mínimo).
Estadísticas por carrera:número de estudiantes, promedio general y mejor estudiante.
Persistencia: guardar y cargar datos en JSON.

Herramientas

Php,Arreglos asociativos y multidimensionales y programación Orientada a Objetos.

Autor:WarryTex
TALLER 5

