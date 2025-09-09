<?php
include 'funciones_gimnasio.php';

$Membresias = ["basico" => "80", "premiun" => 120 ,"Vip" => "180", "Familiar" => 250,"corporativa" => "300"];
$miembros = ["Juan Pérez" => "Madrid", "profesion" => "Ingeniero"];
$infoCompleta = array_merge($infoPersona1, $infoPersona2);
echo "</br>Información completa de la persona:</br>";
print_r($infoCompleta);

?>

