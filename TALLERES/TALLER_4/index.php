<?php
require_once "Empresa.php";

$empresa = new Empresa();

$gerente1 = new Gerente("Irene Rodriguez", 1, 3000, "Recursos Humanos");
$gerente1->asignarBono(500);

$dev1 = new Desarrollador("Jhony Santamaria", 2, 2000, "Phython", "Senior");
$dev2 = new Desarrollador("Emily Perez", 3, 1500, "Java", "Senior");

$empresa->agregarEmpleado($gerente1);
$empresa->agregarEmpleado($dev1);
$empresa->agregarEmpleado($dev2);

echo "<h2>Lista de empleados:</h2>";
$empresa->listarEmpleados();

echo "<h2>Nómina Total:</h2>";
echo "La nómina total es: $" . $empresa->calcularNominaTotal() . "<br>";

echo "<h2>Evaluaciones de Desempeño:</h2>";
$empresa->evaluarEmpleados();
