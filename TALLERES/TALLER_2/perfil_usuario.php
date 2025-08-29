<?php
$nombre_completo = "Emily Yassiel Pérez Rodríguez";
$edad = 22;
$correo = "emilyperez@utp.ac.pa";
$telefono = "6969-7969";


define("OCUPACION", "Estudiante");
echo "Nombre completo: " . $nombre_completo . "<br>";
echo "Edad: " . $edad . "<br>";
print "Correo: " . $correo . "<br>";
print "Teléfono: " . $telefono . "<br>";
printf("Ocupación: %s<br>", OCUPACION);

// Uso de var_dump para mostrar tipo y valor
echo "<br>--- Información detallada con var_dump ---<br>";
var_dump($nombre_completo);
echo "<br>";
var_dump($edad);
echo "<br>";
var_dump($correo);
echo "<br>";
var_dump($telefono);
echo "<br>";
var_dump(OCUPACION);
?>
