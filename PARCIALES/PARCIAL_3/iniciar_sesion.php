<?php
session_start();

$_SESSION['usuario'] = "profe25";
$_SESSION['contrasena'] = "DS7EP";

$_SESSION['usuario'] = "estu25";
$_SESSION['contrasena'] = "DS7TP";

echo "Sesión iniciada para " . $_SESSION['usuario'];

?>